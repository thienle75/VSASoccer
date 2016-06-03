<?php

namespace App\Http\Controllers;

use App\Assist;
use App\Goal;
use App\PlayerTeams;
use App\Season;
use App\Stat;
use App\Player;
use App\Team;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Input;
use Redirect;

class StatController extends Controller
{
    /**
     * @param null $seasonId
     * @return mixed
     */
    public function index($seasonId = null)
    {
        $theStats = [];

        //pull all players
        $players = Player::all();
        foreach($players as $player){
            $season = Season::find($seasonId);
            if($season){
                $games = $season->games()->getResults();

                $stats = [];
                foreach($games as $game){
                    $stat = $player->getStatsForGame($game->id);
                    if($stat!==null) {
                        $stats[] = $stat;
                    }
                }
            }else{
                $stats = $player->stats()->getResults();
            }

            $calculateStats = $player->calculateStats($stats, $seasonId);

            $sortDir = SORT_DESC;
            $sortType = SORT_NUMERIC;

            if($calculateStats['rating']['teamPointsRating'] != 'N/A'){
                if(Input::has('dir')){
                    if(Input::get('dir')=='asc'){
                        $sortDir = SORT_ASC;
                    }elseif(Input::get('dir')=='desc'){
                        $sortDir = SORT_DESC;
                    }
                }

                if(Input::has('column')){
                    switch(Input::get('column')){
                        case 'name':
                            $sortingArray[] = $player->formalName();
                            $sortType = SORT_STRING;
                            break;
                        case 'position':
                            $sortingArray[] = $player->position;
                            $sortType = SORT_STRING;
                            break;
                        case 'games_played':
                        case 'excuses':
                        case 'team_points':
                        case 'potm':
                        case 'potmn':
                        case 'nomination_points':
                        case 'assists':
                        case 'goals':
                        case 'own_goals':
                        case 'team_spirit_points':
                            $sortingArray[] = $calculateStats[Input::get('column')];
                            break;
                    }
                }else{
                    $sortingArray[] = $player->formalName();
                }


                $theStats[] = [
                    'name' => $player->formalName(),
                    'stats' => $calculateStats,
                    'position' =>$player->position,
                    'id' => $player->id
                ];
            }
        }

        if(count($theStats)>0) {
            array_multisort($sortingArray, $sortDir, $sortType, $theStats);
        }

        $seasons = Season::all();

        return View::make('/site.stats.index', [
            'selectedSeason' => $seasonId,
            'seasons' => $seasons,
            'stats'=> $theStats
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getRatings($seasonId = null)
    {
        $theStats = [];
        $sortingArray = [];

        //pull all players
        $players = Player::all();
        foreach($players as $player){
            $season = Season::find($seasonId);
            if($season){
                $games = $season->games()->getResults();

                $stats = [];
                foreach($games as $game){
                    $stat = $player->getStatsForGame($game->id);
                    if($stat!==null) {
                        $stats[] = $stat;
                    }
                }
            }else{
                $stats = $player->stats()->getResults();
            }

            $calculateStats = $player->calculateStats($stats, $seasonId);

            $sortDir = SORT_DESC;
            $sortType = SORT_NUMERIC;

            if($calculateStats['rating']['teamPointsRating'] != 'N/A'){
                if(Input::has('dir')){
                    if(Input::get('dir')=='asc'){
                        $sortDir = SORT_ASC;
                    }elseif(Input::get('dir')=='desc'){
                        $sortDir = SORT_DESC;
                    }
                }

                if(Input::has('column')){
                    switch(Input::get('column')){
                        case 'name':
                            $sortingArray[] = $player->formalName();
                            $sortType = SORT_STRING;
                            break;
                        case 'position':
                            $sortingArray[] = $player->position;
                            $sortType = SORT_STRING;
                            break;
                        case 'team_player_rating':
                            $sortingArray[] = $calculateStats['rating']['teamPlayerRating'];
                            break;
                        case 'player_rating':
                            $sortingArray[] = $calculateStats['rating']['playerRating'];
                            break;
                        case 'team_points_rating':
                            $sortingArray[] = $calculateStats['rating']['teamPointsRating'];
                            break;
                        case 'potm_rating':
                            $sortingArray[] = $calculateStats['rating']['potmRating'];
                            break;
                        case 'potmn_rating':
                            $sortingArray[] = $calculateStats['rating']['potmnRating'];
                            break;
                        case 'assists_rating':
                            $sortingArray[] = $calculateStats['rating']['assistsRating'];
                            break;
                        case 'goals_rating':
                            $sortingArray[] = $calculateStats['rating']['goalsRating'];
                            break;

                    }
                }else{
                    $sortingArray[] = $player->formalName();
                }


                $theStats[] = [
                    'name' => $player->formalName(),
                    'stats' => $calculateStats,
                    'position' =>$player->position,
                    'id' => $player->id
                ];
            }
        }

        array_multisort($sortingArray, $sortDir, $sortType, $theStats);

        $seasons = Season::all();

        return View::make('site.stats.ratings.index', [
            'selectedSeason' => $seasonId,
            'seasons' => $seasons,
            'stats'=> $theStats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if(Auth::user()->authorized()) {
            $team = Team::find(Input::get('teamId'));
            $game = $team->game()->getResults();
            $season = $game->season()->getResults();
            $player = Player::find(Input::get('playerId'));

            //render view
            return View::make('site.stats.edit', [
                'season' => $season,
                'game' => $game,
                'team' => $team,
                'player' => $player,
                'stat' => [],
                'action' => 'create'
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
        $team = Team::find(Input::get('teamId'));
        $game = $team->game()->getResults();
        $season = $game->season()->getResults();

        $stat = Stat::create([
            'team_points' => Input::get('team_points'),
            'player_of_the_match' => Input::get('player_of_the_match'),
            'player_of_the_match_nomination' => Input::get('player_of_the_match_nomination'),
            'player_of_the_match_nomination_points' => Input::get('player_of_the_match_nomination_points'),
            'team_spirit_points' => Input::get('team_spirit_points'),
            'is_captain' => Input::get('is_captain')
        ]);

        $relation = PlayerTeams::where('team_id','=',Input::get('teamId'))
            ->where('player_id','=',Input::get('playerId'))->first();

        $relation->stat_id = $stat->id;

        if($relation->save()){
            return Redirect::route('stats.edit',['statId'=>$stat->id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if(Auth::user()->authorized()) {
            $stat = Stat::find($id);

            $team = $stat->team()->first();
            $game = $team->game()->getResults();
            $season = $game->season()->getResults();
            $player = $stat->player()->first();

            //render view
            return View::make('site.stats.edit', [
                'season' => $season,
                'game' => $game,
                'team' => $team,
                'player' => $player,
                'stat' => $stat,
                'action' => 'edit'
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
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $stat = Stat::find($id);

        $stat->team_points = Input::get('team_points');
        $stat->player_of_the_match = Input::get('player_of_the_match');
        $stat->player_of_the_match_nomination = Input::get('player_of_the_match_nomination');
        $stat->player_of_the_match_nomination_points = Input::get('player_of_the_match_nomination_points');
        $stat->team_spirit_points = Input::get('team_spirit_points');
        $stat->is_captain = Input::get('is_captain');

        if($stat->save()){
            return Redirect::route('stats.edit',['statId'=>$id]);
        }
    }

    /**
     * Creates a Goal for the player
     * @param $id
     * @return string
     */
    public function postAddGoal($id)
    {
        $goal = Goal::create([
            'stat_id' => $id,
            'opponent_team_id' => Input::has('teamId') ? Input::get('teamId') : null,
            'own_goal' => Input::get('ownGoal')
        ]);

        if($goal){
            return 'success';
        }
    }

    /**
     * deletes a specific goal
     */
    public function postDeleteGoal()
    {
        $goal = Goal::find(Input::get('goalId'));

        if($goal->delete()){
            return 'success';
        }
    }

    /**
     * creates an Assist for the player
     * @param $id
     * @return string
     */
    public function postAddAssist($id)
    {
        $assist = Assist::create([
            'stat_id' => $id,
            'opponent_team_id' => Input::has('teamId') ? Input::get('teamId') : null,
            'player_id' => Input::has('playerId') ? Input::get('playerId') : null
        ]);

        if($assist){
            return 'success';
        }
    }

    /**
     * deletes a specific assist
     */
    public function postDeleteAssist()
    {
        $assist = Assist::find(Input::get('assistId'));

        if($assist->delete()){
            return 'success';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
