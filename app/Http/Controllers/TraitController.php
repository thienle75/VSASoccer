<?php

namespace App\Http\Controllers;

use App\Player;
use App\PlayerTrait;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;
use View;

class TraitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $traits = PlayerTrait::all();

        return View::make('site.traits.index', [
            'traits' => $traits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->authorized()) {
            return View::make('site.traits.edit', [
                'trait' => [],
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trait = PlayerTrait::create([
            'name' => Input::get('name'),
            'description' => Input::get('description')
        ]);

        if($trait){
            return Redirect::route('traits.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->authorized()) {
            $trait = PlayerTrait::find($id);

            return View::make('site.traits.edit', [
                'trait' => $trait,
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trait = PlayerTrait::find($id);
        $trait->name = Input::get('name');
        $trait->description = Input::get('description');

        if($trait->save()){
            return Redirect::route('traits.index');
        }else{
            $errors = new MessageBag([
                'errors' => 'Problem Saving'
            ]);

            return redirect()->to('traits.show',['traitId'=>$id])->with([
                'errors' => $errors
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
