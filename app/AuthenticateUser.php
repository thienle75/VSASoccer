<?php

namespace App;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\Repositories\UserRepository; use Request;

class AuthenticateUser {

    private $socialite;
    private $auth;
    private $users;

    /**
     * @param Socialite $socialite
     * @param Guard $auth
     * @param UserRepository $users
     */
    public function __construct(Socialite $socialite, Guard $auth, UserRepository $users) {
        $this->socialite = $socialite;
        $this->users = $users;
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param $listener
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute($request, $listener, $provider) {
        if (!$request) return $this->getAuthorizationFirst($provider);
        $socialite_user = $this->getSocialUser($provider);
        if(!strpos($socialite_user->email, '@engagepeople.com')){
            return $listener->invalidEmail();
        }else{
            $user = $this->users->findByUserNameOrCreate($socialite_user, $provider);

            $this->auth->login($user, true);

            if($this->auth->check()){
                $player = $user->player()->getResults();

                if(!$player){
                    $firstName = $socialite_user['name']['givenName'];
                    $lastName = $socialite_user['name']['familyName'];

                    $player = Player::where('first_name','=',$firstName)
                        ->where('last_name','=',$lastName)
                        ->first();

                    if($player){
                        $player->user_id = $user->id;
                        $player->save();

                        return $listener->userHasLoggedIn();
                    }else{
                        $this->auth->logout();

                        return $listener->playerNotFound();
                    }
                }else{
                    return $listener->userHasLoggedIn();
                }
            }else{
                return $listener->failedLogin();
            }
        }
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getAuthorizationFirst($provider) {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return \Laravel\Socialite\Contracts\User
     */
    private function getSocialUser($provider) {
        return $this->socialite->driver($provider)->user();
    }
}
