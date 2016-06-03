<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class PlayerTrait extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'traits';

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Grabs the Player that scored the goal
     * @return mixed
     */
    public function players()
    {
        return $this->belongsToMany('App\Player', 'player_traits', 'trait_id', 'player_id');
    }
}
