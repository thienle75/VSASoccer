<?php

namespace App\Http\Controllers;


use App\Player;
use Auth;
use App\Http\Requests;
use Exception;
use Illuminate\Support\Facades\View;
use Redirect;
use Request;

class TeamBuilderController extends Controller

{
    /**
     * @return mixed
     */
    public function index()
    {
        return View::make('site.teamBuilder.index');
    }

    /**
     * @param $formation
     * @return mixed
     */
    public function getFormation($formation)
    {
        $players = Player::all();

        return View::make('site.teamBuilder.formations.'.$formation, [
            'players' => $players
        ]);
    }

    /**
     * @return mixed
     */
    public function getSix()
    {
        $players = Player::all();

        $formations = [
            'threeTwo' => '3-2',
            'twoThree' => '2-3',
            'twoOneTwo' => '2-1-2'
        ];

        return View::make('site.teamBuilder.six', [
            'players' => $players,
            'formations' => $formations
        ]);
    }

    /**
     * @return mixed
     */
    public function getSeven()
    {
        $players = Player::all();

        $formations = [
            'threeThree' => '3-3',
            'fourTwo' => '4-2',
            'threeOneTwo' => '3-1-2'
        ];

        return View::make('site.teamBuilder.seven', [
            'players' => $players,
            'formations' => $formations
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function getPlayerInfo($id)
    {
        $player = Player::find($id);

        $result = [
            'name' => $player->formalName(),
            'position' => $player->position,
            'tr' => $player->teammate_rating,
            'pr' => $player->player_rating
        ];

        return json_encode($result);
    }


    /**
     * @return string
     */
    public function getChemistry()
    {
        $player1_id = \Input::get('player1_id');
        $player2_id = \Input::get('player2_id');

        $player1 = Player::find($player1_id);
        $player2 = Player::find($player2_id);

        $player1Teams = $player1->teams()->get()->lists('id')->all();
        $teamsTogether = $player2->teams()->whereIN('teams.id',$player1Teams)->getResults();
        $gamesPlayedTogether = count($teamsTogether);
        $gamesWonTogether = 0;

        foreach($teamsTogether as $team)
        {
            if($team->isWinningTeam()){
                $gamesWonTogether++;
            }
        }

        if($gamesPlayedTogether==0){
            $result = [
                "chemistry" => 0.00
            ];
        }else{
            $result = [
                "chemistry" => round(($gamesWonTogether/$gamesPlayedTogether)*100,2)
            ];
        }

        return json_encode($result);
    }

    public function getAutoSelect()
    {
        $players = Player::orderBy('first_name', 'asc')->orderBy('last_name', 'asc')->get();

        return View::make('site.teamBuilder.auto', [
            'players' => $players
        ]);
    }

    public function postBalanceTeams()
    {
        $playerIds = Request::get('players');
        $captainIds = Request::get('captains');
        $numberOfTeams = Request::get('numberOfTeams');

        if(count($captainIds)!=$numberOfTeams){
            return json_encode([
                'status' => -1,
                'msg' => 'Incorrect number of captains selected.'
            ]);
        }else {
            $teams = [];

            $playerIdsToPick = array_diff($playerIds, $captainIds);
            $playersToPick = $this->computePlayerRatings($playerIdsToPick);
            $captainsToPlay = $this->computePlayerRatings($captainIds);

            $count = 0;
            foreach($captainsToPlay as $captainId=>$captainValue) {
                $teams['team'.$count][] = ['id'=>$captainId, 'value'=>$captainValue, 'captain' => true];
                $count++;
            }

            switch ($numberOfTeams) {
                case 2:
                    $trig = 1;
                    foreach($playersToPick as $playerId => $playerValue){
                        if($trig==1){
                            $teams['team0'][] = ['id'=>$playerId, 'value'=>$playerValue, 'captain' => false];
                            $trig = 0;
                        }else{
                            $teams['team1'][] = ['id'=>$playerId, 'value'=>$playerValue, 'captain' => false];
                            $trig = 1;
                        }
                    }
                    $balancedTeams = $this->balanceTeams($teams);

                    break;
                case 3:
                    $trig = 0;

                    foreach($playersToPick as $playerId => $playerValue){
                        if($trig==0){
                            $teams['team0'][] = ['id'=>$playerId, 'value'=>$playerValue, 'captain' => false];
                            $trig = 1;
                        }elseif($trig==1){
                            $teams['team1'][] = ['id'=>$playerId, 'value'=>$playerValue, 'captain' => false];
                            $trig = 2;
                        }else{
                            $teams['team2'][] = ['id'=>$playerId, 'value'=>$playerValue, 'captain' => false];
                            $trig = 0;
                        }
                    }

                    $balancedTeams = $this->balanceTeams($teams);

                    break;
            }

            $resultTeams = [];
            $teamIndx = 0;
            foreach($teams as $idx => $team){
                foreach($team as $player){
                    $resultTeams[$teamIndx][] = Player::find($player['id']);
                }
                $teamIndx++;
            }

            return json_encode([
                'status' => 0,
                'msg' => 'Success',
                'data' => $resultTeams
            ]);
        }
    }

    /**
     * This function attempts to balance the teams
     * @param $teams
     * @return mixed
     */
    public function balanceTeams(&$teams){
        $teamRatings = [];
        foreach($teams as $indx => $team){
            $teamSum = 0;
            foreach($team as $player){
                $teamSum += $player['value'];
            }
            $teamRatings[$indx] = $teamSum/count($team);
        }

        arsort($teamRatings);

        $teamRatingsIndex = [];
        foreach($teamRatings as $indx => $teamValue) {
            $teamRatingsIndex[] = ['indx'=>$indx, 'value'=>$teamValue];
        }

        switch(count($teamRatings)){
            case 2:
                if(abs($teamRatingsIndex[0]['value'] - $teamRatingsIndex[1]['value']) > 0.02){
                    $this->optimizeTeams($teams[$teamRatingsIndex[0]['indx']], $teams[$teamRatingsIndex[1]['indx']]);
                    $this->balanceTeams($teams);
                }else{
                    return $teams;
                }

                break;
            case 3:
                if(abs($teamRatingsIndex[0]['value'] - $teamRatingsIndex[2]['value']) > 0.02){
                    $this->optimizeTeams($teams[$teamRatingsIndex[0]['indx']], $teams[$teamRatingsIndex[2]['indx']]);
                    $this->balanceTeams($teams);
                }else{
                    return $teams;
                }

                break;
        }
    }

    /**
     * This function swaps 1 player from each team in attempt to balance the teams.
     * @param $team1
     * @param $team2
     */
    public function optimizeTeams( &$team1, &$team2)
    {
        try {
            $numPlayersTeam1 = count($team1);
            $numPlayersTeam2 = count($team2);


            //TODO: add player/team restrictions (e.g. Covello's can't be on same team)
            $playerToMove1 = $team1[1];
            $playerToMove2 = $team2[$numPlayersTeam2 - 1];

            $team1[] = $playerToMove2;
            $team2[] = $playerToMove1;

            unset($team1[1]);
            unset($team2[$numPlayersTeam2 - 1]);

            $team1 = array_values($team1);
            $team2 = array_values($team2);
        }catch(Exception $e){
            print_r($team1); die;
        }
    }

    /**
     * This function computes each player's rating
     * @param $playerIdsToPick
     * @return array
     */
    public function computePlayerRatings($playerIdsToPick)
    {
        $playerRatings = [];

        foreach($playerIdsToPick as $playerId)
        {
            $player = Player::find($playerId);

            $playerValue = (($player->player_rating*3) + ($player->teammate_rating*5) +  ($player->player_class*10))/18;

            $playerRatings[$playerId] = $playerValue;
        }

        arsort($playerRatings);

        return $playerRatings;
    }
}
