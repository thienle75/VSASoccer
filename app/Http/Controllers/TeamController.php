<?php

namespace App\Http\Controllers;

use App\Game;
use App\PlayerTeams;
use App\Stat;
use App\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Input;
use View;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * @param $seasonId
     * @param $gameId
     * @return \Illuminate\Contracts\View\View
     */
    public function create($seasonId, $gameId)
    {
        if(Auth::user()->authorized()) {
            $game = Game::find($gameId);
            $teamsCount = $game->teams()->count();

            //render view
            return View::make('site.games.teams.create', [
                'game' => $game,
                'teamCount' => $teamsCount
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
     * This function removes a player from a given team
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     * @return string
     */
    public function postRemovePlayer($seasonId, $gameId, $teamId)
    {
        $relation = PlayerTeams::where('team_id','=',$teamId)
            ->where('player_id','=',Input::get('playerId'))
            ->first();

        $relation->delete();

        if($relation) {
            return 'success';
        }
    }


    /**
     * This function adds a player to a given team
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     * @return string
     */
    public function postAddPlayer($seasonId, $gameId, $teamId)
    {
        $stat = Stat::create();

        $relations = PlayerTeams::create([
            'team_id'=>$teamId,
            'player_id' => Input::get('playerId'),
            'stat_id' => $stat->id
        ]);

        if($relations) {
            return 'success';
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param $seasonId
     * @param $gameId
     * @return string
     */
    public function store($seasonId, $gameId)
    {
        $team = Team::create([
            'game_id' => $gameId,
            'color' => Input::get('teamColor')
        ]);

        if($team){
            return 'success';
        }
    }

    /**
     * Display the specified resource.
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     */
    public function show($seasonId, $gameId, $teamId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     */
    public function edit($seasonId, $gameId, $teamId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     */
    public function update($seasonId, $gameId, $teamId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param $seasonId
     * @param $gameId
     * @param $teamId
     * @return string
     */
    public function destroy($seasonId, $gameId, $teamId)
    {
        $game = Game::find($gameId);
        $team = Team::find($teamId);

        $currentDate = new Carbon('2 Days Ago');
        if($currentDate->diff(new Carbon($game->date))->invert){
            //TODO: the game has passed
            return 'The game has already passed. Can not delete.';
        }else{
            //the game hasn't passed yet
            $numPlayers = $team->players()->count();

            if($numPlayers>0){
                return 'This team has players in it. It can not be deleted';
            }else{
                $team->delete();
                return 'Team successfully deleted';
            }
        }
    }
}
