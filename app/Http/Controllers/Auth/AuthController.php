<?php

namespace App\Http\Controllers\Auth;

use App\AuthenticateUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\MessageBag;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @param AuthenticateUser $authenticateUser
     * @param Request $request
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login(AuthenticateUser $authenticateUser, Request $request, $provider)
    {
        return $authenticateUser->execute($request->all(), $this, $provider);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userHasLoggedIn() {
        return redirect()->intended();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invalidEmail() {
        $errors = new MessageBag(['Please use your engage people email address.']);
        return redirect('/login')->with(['errors'=> $errors]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function playerNotFound() {
        $errors = new MessageBag(['Player not found in the system.']);
        return redirect('/login')->with(['errors'=> $errors]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failedLogin() {
        $errors = new MessageBag(['Failed to login.']);
        return redirect('/login')->with(['errors'=> $errors]);
    }
}
