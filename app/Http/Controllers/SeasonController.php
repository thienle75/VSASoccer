<?php

namespace App\Http\Controllers;

use App\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $seasons = Season::all();
        return View::make('site.seasons.index', [
            'seasons'=>$seasons
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
            //render view
            return View::make('site.seasons.edit', [
                'season' => [],
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
        $seasons = Season::all();

        $season = new Season();
        $season->name = Input::get('name');
        $start_date = new Carbon(Input::get('start_date'));
        $season->start_date = $start_date->toDateTimeString();
        $end_date = new Carbon(Input::get('end_date'));
        $season->end_date = $end_date->toDateTimeString();

        if($season->save()){
            return Redirect::route('seasons.index', [
                'seasons' => $seasons
            ]);
        }else{
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
            $season = Season::find($id);

            //render view
            return View::make('site.seasons.edit', [
                'season' => $season,
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
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $seasons = Season::all();

        $season = Season::find($id);
        $season->name = Input::get('name');
        $start_date = new Carbon(Input::get('start_date'));
        $season->start_date = $start_date->toDateTimeString();
        $end_date = new Carbon(Input::get('end_date'));
        $season->end_date = $end_date->toDateTimeString();

        if($season->save()){
            return Redirect::route('seasons.index', [
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
        //
    }
}
