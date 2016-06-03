<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class PlayerTeams extends Model
{
    use RevisionableTrait;

    /**
* The database table used by the model.
*
* @var string
*/
    protected $table = 'player_teams';

    protected $fillable = [
        'team_id',
        'player_id',
        'stat_id'
    ];
}
