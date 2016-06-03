<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class Assist extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assists';

    protected $fillable = [
        'stat_id',
        'opponent_team_id',
        'player_id'
    ];

    /**
     * Grabs the related person that received the assist
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Player');
    }

    /**
     * Grabs the related stat object
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
