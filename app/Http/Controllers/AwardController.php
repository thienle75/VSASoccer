<?php

namespace App\Http\Controllers;

use App\Award;
use App\AwardType;
use App\Player;
use App\Season;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;
use View;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $seasons = Season::all();

        return View::make('site.awards.index', [
            'seasons' => $seasons
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
            $award_types = AwardType::get()->lists('name', 'id')->all();
            $players = Player::orderBy('first_name')->get();
            $seasons = Season::get()->lists('name', 'id')->all();

            $thePlayers = [];
            foreach ($players as $player) {
                $thePlayers[$player->id] = $player->formalName();
            }

            return View::make('site.awards.edit', [
                'award_types' => $award_types,
                'players' => $thePlayers,
                'seasons' => $seasons,
                'award' => [],
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
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $award = Award::create([
            'award_type_id' => Input::get('award_type_id'),
            'player_id' => Input::get('player_id'),
            'season_id' => Input::get('season_id')
        ]);

        if($award){
            return Redirect::route('awards.index');
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
        if (Auth::user()->authorized()) {
            $award = Award::find($id);
            $award_types = AwardType::get()->lists('name', 'id')->all();
            $players = Player::orderBy('first_name')->get();
            $seasons = Season::get()->lists('name', 'id')->all();

            $thePlayers = [];
            foreach ($players as $player) {
                $thePlayers[$player->id] = $player->formalName();
            }

            return View::make('site.awards.edit', [
                'award_types' => $award_types,
                'players' => $thePlayers,
                'seasons' => $seasons,
                'award' => $award,
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $award = Award::find($id);
        $award->award_type_id = Input::get('award_type_id');
        $award->player_id = Input::get('player_id');
        $award->season_id = Input::get('season_id');


        if($award->save()){
            return Redirect::route('awards.index');
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
