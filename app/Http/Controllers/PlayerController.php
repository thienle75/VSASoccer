<?php

namespace App\Http\Controllers;

use App\Game;
use App\Player;
use App\Http\Requests;
use App\PlayerTrait;
use App\PlayerTraits;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Redirect;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //paginate the first 20 players in database
        $players = Player::orderBy('first_name', 'asc')->paginate(20);
        //render view
        return View::make('site.players.index', [
            'players' => $players
        ]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()
    {
        if (Auth::user()->authorized()) {
            $players = Player::get()->lists('name', 'id')->all();

            $traits = PlayerTrait::get()->lists('name', 'id')->all();

            //render view
            return View::make('site.players.edit', [
                'player' => [],
                'players' => $players,
                'action' => 'create',
                'traits' => $traits,
                'playerTraits' => []
            ]);
        }

        $errors = new MessageBag([
            'errors' => 'Access Denied'
        ]);

        return redirect()->to('seasons')->with([
            'errors' => $errors
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //get all input from the create player
        $input = Input::all();

        //create a new player object
        $player = new Player();

        //store and save all info into database
        $player->first_name = $input['first_name'];
        $player->last_name = $input['last_name'];
        $player->position = $input['position'];
        $player->status = $input['status'];
        //$player->has_bonus = $input['bonus'];
        //$player->has_penalty = $input['penalty'];

        if ($player->save()) {
            //render view
            return Redirect::route('players.index');
        } else {
            //TODO: add error case

        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //pull all players
        $player = Player::find($id);

        //pull all players stats
        $stats = $player->stats()->getResults();
        $calculatedStats = $player->playerGameStats();

        //pull additional data
        $performance = $this->getPerformanceData($stats);
        $rankings = $this->getRankingData($id);

        $excuses = $player->excuses()->getResults();
        $awards = $player->awards()->getResults();

        $traits = implode(', ',$player->traits()->get()->lists('name')->all());

        return View::make('site.players.show')->with([
            'calculatedStats' =>$calculatedStats,
            'player'=>$player,
            'stats'=>$stats,
            'performance' => $performance,
            'rankings' => $rankings,
            'excuses' => $excuses,
            'awards' => $awards,
            'traits' => $traits
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (Auth::user()->authorized()) {
            $action = 'edit';

            $player = Player::find($id);

            $users = User::all();

            $userEmails = [];

            foreach ($users as $user) {
                $userEmails[$user->id] = $user->email;
            }

            $traits = PlayerTrait::get()->lists('name', 'id')->all();

            $playerTraits = $player->traits()->get()->lists('id')->all();

            return View::make('site.players.edit', [
                'action' => $action,
                'player' => $player,
                'traits' => $traits,
                'playerTraits' => $playerTraits,
                'userEmails' => $userEmails
            ]);
        }

        $errors = new MessageBag([
            'errors' => 'Access Denied'
        ]);

        return redirect()->to('seasons')->with([
            'errors' => $errors
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //TODO: add validation

        $player = Player::find($id);

        //paginate all players in database
        $players = Player::paginate(20);

        $player->first_name = Input::get('first_name');
        $player->last_name = Input::get('last_name');
        $player->position = Input::get('position');
        $player->status = Input::get('status');

        $traitIds = Input::get('traits');

        $playerTraits = PlayerTraits::where('player_id', '=', $player->id)->get();

        foreach($playerTraits as $playerTrait){
            $playerTrait->delete();
        }


        if($traitIds) {
            foreach ($traitIds as $traitId) {
                PlayerTraits::create([
                    'player_id' => $player->id,
                    'trait_id' => $traitId
                ]);
            }
        }

        if($player->save()){
            //render view
            return Redirect::route('players.index', [
                'players' => $players
            ]);
        }else{
            //TODO: add error case
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //find player to delete
        $player = Player::find($id);

        if ($player->delete()) {
            return 'player was deleted successfully';
        } else {
            //TODO: add error case
            return 'player was not deleted successfully';
        }

    }

    /**
     * This function organizes the statistical data for the graphs
     * @param $stats
     * @return array
     */
    public function getPerformanceData($stats)
    {
        //Graphs
        $numStats = count($stats);

        $goalsAssistsVSgames = [];
        $cumulativeGoals = 0;
        $cumulativeAssists = 0;

        $teamPointsVSgames = [];
        $cumulativeTeamPoints = 0;

        $goalDiffVSgames = [];
        $diff = 0;
        $cumulativeGoalDiff = 0;

        foreach($stats as $stat){
            $playerTeam = $stat->team()->first();
            $game = $playerTeam->game()->getResults();

            //Goals vs Assits vs Games
            $cumulativeGoals += count($stat->goalsByType());
            $cumulativeAssists += $stat->assists()->count();
            $date = new Carbon($game->date);

            $goalsAssistsVSgames[] = [
                [$date->year, $date->month, $date->day],
                $cumulativeGoals,
                $cumulativeAssists
            ];

            //Team Points vs. Games
            $cumulativeTeamPoints += $stat->team_points;
            $teamPointsVSgames[] = [
                [$date->year, $date->month, $date->day],
                $cumulativeTeamPoints
            ];

            //Goal Difference vs. Game
            $teams = $game->teams()->getResults();
            $teamCount = count($teams);

            $score = [];

            foreach ($teams as $team) {
                $score[$team->color] = 0;
            }

            foreach ($teams as $team) {
                $teamGoals = $team->getTeamGoals();
                $score[$team->color] += $teamGoals['goals'];

                $ownGoalUniques = array_unique($teamGoals['ownGoals']);

                foreach ($ownGoalUniques as $ownGoalUnique) {
                    $score[$ownGoalUnique] += count(array_keys($teamGoals['ownGoals'], $ownGoalUnique));
                }
            }

            arsort($score);
            $scores = array_values($score);

            if(array_search($playerTeam->color,array_keys($score))==0){
                $diff = $scores[0] - $scores[1];
            }elseif(array_search($playerTeam->color,array_keys($score))==1){
                $diff = $scores[1] - $scores[0];
            }elseif(array_search($playerTeam->color,array_keys($score))==2){
                $diff = $scores[2] - $scores[0];
            }

            $cumulativeGoalDiff = $cumulativeGoalDiff + $diff;
            $goalDiffVSgames[] = [
                [$date->year, $date->month, $date->day],
                $cumulativeGoalDiff
            ];
        }
        if ($numStats > 0) {
            return [
                'goalsAssistsVSgames' => [
                    'title' => 'Cumulative Goals & Assists vs. Games',
                    'goalsRate' => round($cumulativeGoals / $numStats, 2),
                    'assistsRate' => round($cumulativeAssists / $numStats, 2),
                    'data' => $goalsAssistsVSgames
                ],
                'teamPointsVSgames' => [
                    'title' => 'Cumulative Team Points vs. Games',
                    'data' => $teamPointsVSgames
                ],
                'goalDiffVSgames' => [
                    'title' => 'Team Goal Difference vs. Games',
                    'data' => $goalDiffVSgames
                ]
            ];
        } else {
            return ['goalsAssistsVSgames' => [
                'title' => 'Cumulative Goals & Assists vs. Games',
                'goalsRate' => 0.00,
                'assistsRate' => 0.00,
                'data' => $goalsAssistsVSgames
            ],
                'teamPointsVSgames' => [
                'title' => 'Cumulative Team Points vs. Games',
                'data' => $teamPointsVSgames
            ],
                'goalDiffVSgames' => [
                'title' => 'Team Goal Difference vs. Games',
                'data' => $goalDiffVSgames
            ]
            ];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function getRankingData($id)
    {
        $players = Player::all();
        //stores the player who we are finding similar ranks for
        $player = [];
        $simRankPlayers = [];

        foreach($players as $player){
            $stats = $player->stats()->getResults();
            $calculateStats = $player->calculateStats($stats);

            $sortingArray['goals'][] = $calculateStats['goals'];
            $sortingArray['assists'][] = $calculateStats['assists'];
            $sortingArray['potmn'][] = $calculateStats['potmn'];
            $sortingArray['potm'][] = $calculateStats['potm'];
            $sortingArray['tp'][] = $calculateStats['team_points'];
            $sortingArray['nomination_points'][] = $calculateStats['nomination_points'];
            $sortingArray['team_spirit_points'][] = $calculateStats['team_spirit_points'];
            $sortingArray['own_goals'][] = $calculateStats['own_goals'];

            $theStats[] = [
                'name' => $player->formalName(),
                'stats' => $calculateStats,
                'position' =>$player->position,
                'id' => $player->id
            ];
        }

        $goalRankings = $this->calculateRank($id,'goals', $theStats, $sortingArray['goals']);
        $assistRankings = $this->calculateRank($id,'assists', $theStats, $sortingArray['assists']);
        $potmnRankings = $this->calculateRank($id,'potmn', $theStats, $sortingArray['potmn']);
        $potmRankings = $this->calculateRank($id,'potm', $theStats, $sortingArray['potm']);
        $tpRankings = $this->calculateRank($id,'team_points', $theStats, $sortingArray['tp']);
        $nomPointRankings = $this->calculateRank($id,'nomination_points', $theStats, $sortingArray['nomination_points']);

        $rankings = [
            'goals' => $goalRankings,
            'assists' => $assistRankings,
            'potmn' => $potmnRankings,
            'potm' => $potmRankings,
            'team_points' => $tpRankings,
            'nomination_points' => $nomPointRankings,
        ];

        return $rankings;
    }

    /**
     * sorts all goals to find the 3 closest players to you on either side of the scoring race
     * @param $id
     * @param $statToGetRank
     * @param $theStats
     * @param $sortingArray
     * @return array
     */
    public function calculateRank($id, $statToGetRank, $theStats, $sortingArray)
    {
        array_multisort($sortingArray, SORT_DESC, SORT_NUMERIC, $theStats);

        $count = 0;
        $finalSortArray = [];

        foreach ($theStats as $theStat) {

            if ($theStat['id'] == $id) {
                $player = $theStat;
                break;
            }
            $count++;
        }

        //checks if all three indexes are valid above the player. If they aren't then it only adds the valid ones to similar ranked players
        if (array_key_exists($count - 1, $theStats)) {
            if (array_key_exists($count - 2, $theStats)) {
                $simRankPlayers[] = [
                    'name' => $theStats[$count - 2]['name'],
                    $statToGetRank => $theStats[$count - 2]['stats'][$statToGetRank],
                    'rank' => $count - 2,
                    'id' => $theStats[$count - 2]['id']
                ];
                $simRankPlayers[] = [
                    'name' => $theStats[$count - 1]['name'],
                    $statToGetRank => $theStats[$count - 1]['stats'][$statToGetRank],
                    'rank' => $count - 1,
                    'id' => $theStats[$count - 1]['id']
                ];

                $finalSortArray[] = $theStats[$count - 2]['stats'][$statToGetRank];
                $finalSortArray[] = $theStats[$count - 1]['stats'][$statToGetRank];
            } else {
                $simRankPlayers[] = [
                    'name' => $theStats[$count - 1]['name'],
                    $statToGetRank => $theStats[$count - 1]['stats'][$statToGetRank],
                    'rank' => $count - 1,
                    'id' => $theStats[$count - 1]['id']
                ];
                $simRankPlayers[] = [
                    'name' => $theStats[$count + 3]['name'],
                    $statToGetRank => $theStats[$count + 3]['stats'][$statToGetRank],
                    'rank' => $count + 3,
                    'id' => $theStats[$count + 3]['id']
                ];

                $finalSortArray[] = $theStats[$count - 1]['stats'][$statToGetRank];
                $finalSortArray[] = $theStats[$count + 3]['stats'][$statToGetRank];
            }

        } else {
            $simRankPlayers[] = [
                'name' => $theStats[$count+4]['name'],
                $statToGetRank => $theStats[$count+4]['stats'][$statToGetRank],
                'rank' => $count + 4,
                'id' => $theStats[$count+4]['id']
            ];
            $simRankPlayers[] = [
                'name' => $theStats[$count + 3]['name'],
                $statToGetRank => $theStats[$count + 3]['stats'][$statToGetRank],
                'rank' => $count + 3,
                'id' => $theStats[$count + 3]['id']
            ];

            $finalSortArray[] = $theStats[$count + 4]['stats'][$statToGetRank];
            $finalSortArray[] = $theStats[$count + 3]['stats'][$statToGetRank];
        }

        //adds the current player to the middle of the similar rank array
        $simRankPlayers[] = [
            'name' => $theStats[$count]['name'],
            $statToGetRank => $theStats[$count]['stats'][$statToGetRank],
            'rank' => $count,
            'id' => $theStats[$count]['id']
        ];

        $finalSortArray[] = $theStats[$count]['stats'][$statToGetRank];

        //checks if all three indexes are valid below the player. If they aren't then it only adds the valid ones to similar ranked players
        if (array_key_exists($count+1, $theStats)) {
            if (array_key_exists($count+2, $theStats)) {
                $simRankPlayers[] = [
                    'name' => $theStats[$count+1]['name'],
                    $statToGetRank => $theStats[$count+1]['stats'][$statToGetRank],
                    'rank' => $count+1,
                    'id' => $theStats[$count+1]['id']
                ];
                $simRankPlayers[] = [
                    'name' => $theStats[$count+2]['name'],
                    $statToGetRank => $theStats[$count+2]['stats'][$statToGetRank],
                    'rank' => $count+2,
                    'id' => $theStats[$count+2]['id']
                ];

                $finalSortArray[] = $theStats[$count + 1]['stats'][$statToGetRank];
                $finalSortArray[] = $theStats[$count + 2]['stats'][$statToGetRank];
            } else {
                $simRankPlayers[] = [
                    'name' => $theStats[$count+1]['name'],
                    $statToGetRank => $theStats[$count+1]['stats'][$statToGetRank],
                    'rank' => $count+1,
                    'id' => $theStats[$count+1]['id']
                ];
                $simRankPlayers[] = [
                    'name' => $theStats[$count-3]['name'],
                    $statToGetRank => $theStats[$count-3]['stats'][$statToGetRank],
                    'rank' => $count-3,
                    'id' => $theStats[$count-3]['id']
                ];

                $finalSortArray[] = $theStats[$count + 1]['stats'][$statToGetRank];
                $finalSortArray[] = $theStats[$count - 3]['stats'][$statToGetRank];
            }
        } else {
            $simRankPlayers[] = [
                'name' => $theStats[$count-4]['name'],
                $statToGetRank => $theStats[$count-4]['stats'][$statToGetRank],
                'rank' => $count-4,
                'id' => $theStats[$count-4]['id']
            ];
            $simRankPlayers[] = [
                'name' => $theStats[$count-3]['name'],
                $statToGetRank => $theStats[$count-3]['stats'][$statToGetRank],
                'rank' => $count-3,
                'id' => $theStats[$count-3]['id']
            ];

            $finalSortArray[] = $theStats[$count - 4]['stats'][$statToGetRank];
            $finalSortArray[] = $theStats[$count - 3]['stats'][$statToGetRank];
        }

        array_multisort($finalSortArray, SORT_DESC, SORT_NUMERIC, $simRankPlayers);

        //returns the player object for the three closest players who have scored more and less goals to the selected player
        return $simRankPlayers;
    }

}
