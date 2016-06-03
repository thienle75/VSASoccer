<?php

namespace App\Http\Controllers;

use App\Game;
use App\Player;
use DB;
use App\Http\Requests;
use View;

class ChemistryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::all()->take(20);

        $chemistryData = [];

        $gameScores = $this->getGameScores();

        foreach($players as $player1){
            foreach($players as $player2){
                $chemistryData[$player1->id]['player1'] = $player1;
                $chemistryData[$player1->id]['players'][] = [
                    'player2'=>$player2,
                    'chemistry' => $this->getChemistry($player1, $player2, $gameScores)
                ];
            }
        }

        return View::make('site.chemistry.index')->with(['chemistryData'=>$chemistryData]);
    }

    public function getChemistry($player1, $player2, $gameScores)
    {
        $player1Teams = $player1->teams()->get()->lists('id')->all();
        $teamsTogether = $player2->teams()->whereIN('teams.id',$player1Teams)->getResults();
        $gamesPlayedTogether = count($teamsTogether);
        $gamesWonTogether = 0;

        if($gamesPlayedTogether!=0){
            foreach($teamsTogether as $team)
            {
                $gameScore = $gameScores[$team->game_id];

                arsort($gameScore);

                if($team->color == array_keys($gameScore)[0]){
                    $gamesWonTogether++;
                }
            }

            $gamesWinningRatio = round($gamesWonTogether/$gamesPlayedTogether*100,2);
        }else{
            $gamesWinningRatio = 0;
        }

        return $gamesWinningRatio;
    }

    /**
     * Computes the game scores for all games
     * @return array
     */
    private function getGameScores()
    {
        $scores = DB::table('games')
            ->selectRAW('COUNT(*) as `goals`, teams.color, games.id as game_id')
            ->leftJoin('teams', 'teams.game_id', '=', 'games.id')
            ->leftJoin('player_teams', 'player_teams.team_id', '=', 'teams.id')
            ->leftJoin('stats', 'player_teams.stat_id', '=', 'stats.id')
            ->join('goals', 'goals.stat_id', '=', 'stats.id')
            ->where('goals.own_goal','=',0)
            ->groupBy('teams.color')
            ->groupBy('games.id')
            ->orderBy('games.id','ASC')
            ->get();

        $ownGoals = DB::table('games')
            ->selectRAW('COUNT(*) as `own_goals`, (SELECT color FROM teams where id = goals.opponent_team_id) as `pointFor`, games.id as game_id')
            ->leftJoin('teams', 'teams.game_id', '=', 'games.id')
            ->leftJoin('player_teams', 'player_teams.team_id', '=', 'teams.id')
            ->leftJoin('stats', 'player_teams.stat_id', '=', 'stats.id')
            ->join('goals', 'goals.stat_id', '=', 'stats.id')
            ->where('goals.own_goal','=',1)
            ->groupBy('teams.color')
            ->groupBy('games.id')
            ->orderBy('games.id','ASC')
            ->get();

        $gameScores = [];

        foreach($scores as $score)
        {
            $gameScores[$score->game_id][$score->color] = $score->goals;
        }

        foreach($ownGoals as $ownGoal)
        {
            $gameScores[$ownGoal->game_id][$ownGoal->pointFor] += $ownGoal->own_goals;
        }

        return $gameScores;
    }
}
