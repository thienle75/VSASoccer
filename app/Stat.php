<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class Stat extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stats';

    protected $fillable = [
        'team_points',
        'player_of_the_match',
        'player_of_the_match_nomination',
        'player_of_the_match_nomination_points',
        'team_spirit_points',
        'is_captain'
    ];

    /**
     * Grabs the related Team
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team()
    {
        return $this->belongsToMany('App\Team', 'player_teams', 'stat_id', 'team_id');
    }

    /**
     * Grabs the related Player
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function player()
    {
        return $this->belongsToMany('App\Player', 'player_teams', 'stat_id', 'player_id');
    }

    /**
     * Grabs the related Goals
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goals()
    {
        return $this->hasMany('App\Goal');
    }

    /**
     * sorts the goals into the appropriate arrays
     * @param string $type
     * @return array
     */
    public function goalsByType($type='scored')
    {
        $goals = $this->goals()->getResults();
        $scoredGoals = [];
        $ownGoals = [];

        foreach($goals as $goal){
            if(!$goal->own_goal){
                $scoredGoals[] = $goal;
            }else{
                $ownGoals[] = $goal;
            }
        }

        switch($type){
            case 'scored':
                return $scoredGoals;
            case 'own':
                return $ownGoals;
        }
    }

    /**
     * Grabs the related Assists
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assists()
    {
        return $this->hasMany('App\Assist');
    }

}
