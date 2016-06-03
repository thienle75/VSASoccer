<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\MessageBag;

class UserController extends Controller
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
     *
     * @return Response
     */
    public function create()
    {
        if(\Auth::user()->authorized()) {
            $action = 'create';

            return \View::make('site.users.edit', [
                'user' => [],
                'action' => $action
            ]);
        }else{
            return \Redirect::to('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->authorized()) {
            $user = User::create(\Input::all());

            if ($user) {
                return \Redirect::to('/');
            }
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
        $loggedInUser = \Auth::user();
        $user = User::find($id);

            $action = 'edit';

            return \View::make('site.users.edit', [
                'user' => $user,
                'action' => $action
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
        $user = User::find($id);

        $user->name = \Input::get('name');
        $user->email = \Input::get('email');
        $user->password = bcrypt(\Input::get('password'));

        if($user->save()){
            return \Redirect::to('/');
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
