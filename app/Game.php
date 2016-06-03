<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class Game extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'games';

    protected $fillable = [
        'season_id',
        'date'
    ];

    /**
     * A mutator for the date
     * @param $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        $carbonDate = new Carbon($value);

        return $carbonDate->format('F jS, Y');
    }

    /**
     * returns the games of a season
     */
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    /**
     * returns the teams from a game
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    /**
     * returns an array of all the stats for the game
     * @return array
     */
    public function stats()
    {
        $stats = [];

        foreach($this->teams()->getResults() as $team){
            foreach($team->stats()->getResults() as $stat){
                $stats[] = $stat;
            }
        }

        return $stats;
    }

    /**
     * Returns an array of all the players
     * @return array
     */
    public function players()
    {
        $teams = $this->teams()->getResults();
        $players = [];
        $sortArray = [];

        foreach($teams as $team){
            $teamPlayers = $team->players()->getResults();

            foreach($teamPlayers as $player){
                $players["".$player->id] = $player;
                $sortArray[] = $player->formalName();
            }
        }

        array_multisort($sortArray, SORT_ASC, SORT_STRING, $players);

        return $players;
    }

    /**
     * Returns a collection of the Attendance
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendance()
    {
        return $this->hasMany('App\Attendance');
    }

    /**
     * Returns a collection of the POTMVote
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function potmVotes()
    {
        return $this->hasMany('App\POTMVote');
    }

    /**
     * Returns a collection of the Attendance
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function excuses()
    {
        return $this->attendance()->where('attending','=','excuse');
    }

    /**
     * returns the footage for the game
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function footage()
    {
        return $this->hasMany('App\Footage');
    }



}
