<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Venturecraft\Revisionable\RevisionableTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'provider',
        'provider_id',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return bool
     */
    public function authorized()
    {
        if(Auth::user()->status == 2) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function googleAuth()
    {
        if(Auth::user()->provider == 'google') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function teamCaptain()
    {
        if((Auth::user()->team_captain && date('l') == 'Wednesday') || $this->authorized()) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function player()
    {
        return $this->hasOne('App\Player');
    }

}
