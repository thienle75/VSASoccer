<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Team extends Model
{
    use RevisionableTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

    protected $fillable = [
        'game_id',
        'color'
    ];

    /**
     * returns the related game
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * returns the related players
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany('App\Player', 'player_teams', 'team_id', 'player_id');
    }

    /**
     * returns the related stats
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stats()
    {
        return $this->belongsToMany('App\Stat', 'player_teams', 'team_id', 'stat_id');
    }

    /**
     * computes the average team rating
     * @return float|string
     */
    public function getTeamRating()
    {
        $players = $this->players()->getResults();
        $rating = 0;
        $totalPlayers = count($players);
        if ($totalPlayers > 0) {
            foreach ($players as $player) {
                $rating += ($player->teammate_rating*100);
            }
            return number_format((float)($rating/$totalPlayers), 3, '.', '');
        } else {
            return 0.000;
        }
    }

    /**
     * computes the average player rating
     * @return float|string
     */
    public function getAveragePlayerRating()
    {
        $players = $this->players()->getResults();
        $rating = 0;
        $totalPlayers = count($players);
        if ($totalPlayers > 0) {
            foreach ($players as $player) {
                $rating += ($player->player_rating*100);
            }
            return number_format((float)($rating/$totalPlayers), 3, '.', '');
        } else {
            return 0.000;
        }
    }

    /**
     * computes the team goals
     * @return array
     */
    public function getTeamGoals()
    {
        $stats = $this->stats()->getResults();

        $goals = [
            'goals' => 0,
            'ownGoals' => []
        ];

        $statIds = [];
        foreach ($stats as $stat) {
            if ($stat) {
                $statIds[] = $stat->id;
            }
        }

        $statGoals = Goal::whereIn('stat_id', $statIds)->get();

        foreach ($statGoals as $statGoal) {
            if($statGoal->own_goal){
                $goals['ownGoals'][] = $statGoal->oppTeam()->getResults()->color;
            } else {
                $goals['goals'] += 1;
            }
        }

        return $goals;
    }


    /**
     * This function determines if the team was the winning team
     * @return bool
     */
    public function isWinningTeam()
    {
        $game = $this->game()->getResults();

        $score = DB::table('games')
            ->selectRAW('COUNT(*) as `goals`, teams.color')
            ->leftJoin('teams', 'teams.game_id', '=', 'games.id')
            ->leftJoin('player_teams', 'player_teams.team_id', '=', 'teams.id')
            ->leftJoin('stats', 'player_teams.stat_id', '=', 'stats.id')
            ->join('goals', 'goals.stat_id', '=', 'stats.id')
            ->where('games.id','=',$game->id)
            ->where('goals.own_goal','=',0)
            ->groupBy('teams.color')
            ->lists('goals', 'color');

        $ownGoals = DB::table('games')
            ->selectRAW('COUNT(*) as `own_goals`, (SELECT color FROM teams where id = goals.opponent_team_id) as `pointFor`')
            ->leftJoin('teams', 'teams.game_id', '=', 'games.id')
            ->leftJoin('player_teams', 'player_teams.team_id', '=', 'teams.id')
            ->leftJoin('stats', 'player_teams.stat_id', '=', 'stats.id')
            ->join('goals', 'goals.stat_id', '=', 'stats.id')
            ->where('games.id','=',$game->id)
            ->where('goals.own_goal','=',1)
            ->groupBy('teams.color')
            ->lists('own_goals', 'pointFor');

        foreach($ownGoals as $team=>$numOwnGoals)
        {
            $score[$team] += $numOwnGoals;
        }

        arsort($score);

        if(array_keys($score)[0] == $this->color){
            return true;
        }else{
            return false;
        }
    }
}
