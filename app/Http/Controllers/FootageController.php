<?php

namespace App\Http\Controllers;

use App\Footage;
use App\Game;
use App\Season;
use Auth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;

class FootageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //grab all seasons
        $seasons = Season::all();

        //render view
        return View::make('/site.footage.index', [
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
            $games = Game::get()->lists('date', 'id')->all();

            //render view
            return View::make('site.footage.edit', [
                'video' => [],
                'games' => $games,
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
        //get all input from the create player
        $input = Input::all();

        //create a new player object
        $video = new Footage();

        //store and save all info into database
        $video->url = $input['url'];
        $video->name = $input['name'];
        $video->game_id = $input['game_id'];

        if ($video->save()) {
            //grab all seasons
            $seasons = Season::all();

            //render view
            return Redirect::route('footage.index', [
                'seasons' => $seasons
            ]);
        } else {
            //TODO: add error case

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
        $videoUrl = getVideo($id);
        return View::make('site.footage.index', $videoUrl);
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
            $action = 'edit';

            $games = Game::get()->lists('date', 'id')->all();
            $video = Footage::find($id);

            return View::make('site.footage.edit', [
                'video' => $video,
                'games' => $games,
                'action' => $action
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
        $video = Footage::find($id);

        $video->name = Input::get('name');
        $video->game_id = Input::get('game_id');
        $video->url = Input::get('url');

        if($video->save()){
            //render view
            //grab all seasons
            $seasons = Season::all();

            return Redirect::route('footage.index', [
                'seasons' => $seasons
            ]);
        }else{
            //TODO: add error case
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
        $video = Footage::find($id);

        if($video->delete()) {
            return 'video deleted successfully';
        } else {
            return 'failed to delete video';
        }
    }

    public function getVideo($id)
    {
        $video = Footage::find($id);
        $clip = $video->video($id)->getResults();

        return $clip;
    }
}
