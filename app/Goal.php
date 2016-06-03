<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class Goal extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'goals';

    protected $fillable = [
        'stat_id',
        'opponent_team_id',
        'own_goal'
    ];

    /**
     * Grabs the Player that scored the goal
     * @return mixed
     */
    public function player()
    {
        $stat = $this->stat()->getResults();
        return $stat->player()->first();
    }

    /**
     * Grabs the related Stat object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stat()
    {
        return $this->belongsTo('App\Stat');
    }

    /**
     * Grabs the related opponent Team object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function oppTeam()
    {
        return $this->belongsTo('App\Team', 'opponent_team_id','id');
    }
}
