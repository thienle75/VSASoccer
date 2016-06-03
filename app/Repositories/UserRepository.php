<?php

namespace App\Repositories;

use App\User;

class UserRepository {
    public function findByUserNameOrCreate($userData, $provider) {
        $user = User::where('provider_id', '=', $userData->id)->first();
        if(!$user) {
            $user = User::create([
                'provider_id' => $userData->id,
                'name' => $userData->name,
                'username' => $userData->nickname==null ? '' : $userData->nickname,
                'email' => $userData->email,
                'avatar' => $userData->avatar,
                'provider' => $provider,
                'active' => 1,
                'password' => '',
                'status' => 1
            ]);
        }

        //$this->checkIfUserNeedsUpdating($userData, $user, $provider);
        return $user;
    }

    public function checkIfUserNeedsUpdating($userData, $user, $provider)
    {

        $socialData = [
            'avatar' => $userData->avatar,
            'email' => $userData->email,
            'name' => $userData->name,
            'provider' => $provider,
            'username' => $userData->nickname==null ? '' : $userData->nickname,
            'password' => '',
            'status' => 1
        ];
        $dbData = [
            'avatar' => $user->avatar,
            'email' => $user->email,
            'name' => $user->name,
            'provider' => $provider,
            'username' => $userData->nickname==null ? '' : $userData->nickname,
            'password' => '',
            'status' => 1
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            $user->avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->name = $userData->name;
            $user->nickname = $userData->nickname==null ? '' : $userData->nickname;
            $user->password = '';
            $user->status = 1;
            $user->provider = $provider;
            $user->save();
        }
    }
}