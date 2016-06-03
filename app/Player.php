<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

class Player extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'players';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany('App\Team', 'player_teams', 'player_id', 'team_id');
    }

    public function traits()
    {
        return $this->belongsToMany('App\PlayerTrait', 'player_traits', 'player_id', 'trait_id');
    }

    /**
     * @param $gameId
     * @return mixed
     */
    public function getTeamForGame($gameId)
    {
        $team = $this->teams()->where('game_id', '=', $gameId)->first();

        return $team;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stats()
    {
        return $this->belongsToMany('App\Stat', 'player_teams', 'player_id', 'stat_id');
    }

    /**
     * returns a collection of the related attendance
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendance()
    {
        return $this->hasMany('App\Attendance');
    }

    /**
     * returns a collection of the related potm votes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function potmVotes()
    {
        return $this->hasMany('App\POTMVote');
    }

    /**
     * returns a collection of the related excuses
     * @return mixed
     */
    public function excuses()
    {
        return $this->attendance()->where('attending', '=', 'excuse');
    }

    /**
     * @param $seasonId
     * @return mixed
     */
    public function getExcusesForSeason($seasonId)
    {
        $gameIds = Season::find($seasonId)->games()->get()->lists('id')->all();

        return $this->attendance()->whereIn('game_id',$gameIds)->where('attending', '=', 'excuse');
    }

    /**
     * returns a collection of the related awards
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function awards()
    {
        return $this->hasMany('App\Award');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return array
     */
    public function goals()
    {
        $statIds = $this->stats()
            ->get()
            ->lists('id')
            ->all();

        return Goal::whereIn('stat_id', $statIds);
    }

    /**
     * @return array
     */
    public function assists()
    {
        $statIds = $this->stats()
            ->get()
            ->lists('id')
            ->all();

        return Assist::whereIn('stat_id', $statIds);
    }

    /**
     * This function computes the Ratings
     * @param null $seasonId
     * @param bool|true $format
     * @return array
     */
    public function computeRating($seasonId = null, $format = true)
    {
        if($seasonId){
            $season = Season::find($seasonId);
            $games = $season->games()->getResults();

            $stats = [];
            foreach($games as $game){
                $stat = $this->getStatsForGame($game->id);
                if($stat!==null) {
                    $stats[] = $stat;
                }
            }
        }else{
            $stats = $this->stats()->getResults();
        }

        $teamPlayerRatingBonus = 1.0;
        $playerRatingBonus = 1.0;

        switch($this->position){
            case 'CD':
                $teamPlayerRatingBonus = 2.0;
                $playerRatingBonus = 2.5;
                break;
            case 'GK':
                $teamPlayerRatingBonus = 1.5;
                $playerRatingBonus = 2.0;
                break;
        }

        $gamesPlayed = 0;
        $teamPoints = 0;
        $potmTitles = 0;
        $potmNominations = 0;
        $teamSpirit = 0;

        $gamesPlayedRatio = 0;
        $teamPointsRatio = 0;
        $potmTitlesRatio = 0;
        $potmNominationsRatio = 0;
        $assistsRatio = 0;
        $goalsRatio = 0;
        $teamPlayerRating = 0;
        $playerRating = 0;

        $gamesWon = 0;
        $gamesDraw = 0;
        $gamesLost = 0;

        $wdl_winPercent = 0;
        $wdl_drawPercent = 0;
        $wdl_losePercent = 0;

        foreach($stats as $stat){

            $gamesPlayed++;
            $teamPoints += $stat->team_points;

            if($stat->team_points==5){
                $gamesWon++;
            }elseif($stat->team_points==0 || $stat->team_points==2){
                $gamesLost++;
            }elseif($stat->team_points==3){
                $gamesDraw++;
            }

            $potmTitles += ($stat->player_of_the_match ? 1 : 0);
            $potmNominations += ($stat->player_of_the_match_nomination ? 1 : 0);
            $teamSpirit += $stat->team_spirit_points;
        }

        $goals = $this->getComputedPlayerGoals($stats)['goals'];
        $ownGoals = $this->getComputedPlayerGoals($stats)['ownGoals'];
        $assists = $this->getComputedPlayerAssists($stats);

        $totalGames = 0;
        if($seasonId){
            $gamesMissed= $this->getExcusesForSeason($seasonId)->count();
            $totalGames = $season->games()->where('date','>=',$this->created_at)->count();
        }else{
            $gamesMissed= $this->excuses()->count();
            $seasons = Season::all();

            foreach($seasons as $season){
                $totalGames += $season->games()->where('date','>=',$this->created_at)->count();
            }
        }

        $gamesCounted = $totalGames - $gamesMissed;

        if($gamesPlayed<5){
            $teamPointsRatio = 'N/A';
            $potmTitlesRatio = 'N/A';
            $potmNominationsRatio = 'N/A';
            $assistsRatio = 'N/A';
            $goalsRatio = 'N/A';
            $teamPlayerRating = 'N/A';
            $playerRating = 'N/A';

            $wdl_winPercent = 0;
            $wdl_drawPercent = 0;
            $wdl_losePercent = 0;

            $result = [
                'teamPlayerRating' => $teamPlayerRating,
                'playerRating' => $playerRating,
                'teamPointsRating' => $teamPointsRatio,
                'potmRating' => $potmTitlesRatio,
                'potmnRating' => $potmNominationsRatio,
                'assistsRating' => $assistsRatio,
                'goalsRating' => $goalsRatio,
                'wdlWin' => $wdl_winPercent,
                'wdlDraw' => $wdl_drawPercent,
                'wdlLost' => $wdl_losePercent
            ];
        }else{
            if($gamesCounted > 0 && $gamesPlayed > 0){
                $gamesPlayedRatio = ($gamesPlayed/$gamesCounted);
                $teamPointsRatio = ($teamPoints*$teamPlayerRatingBonus)/($gamesCounted*5);
                $potmTitlesRatio = ($potmTitles/$gamesCounted);
                $potmNominationsRatio = ($potmNominations/$gamesCounted);
                $assistsRatio = ($assists/$gamesPlayed);

                $combinedGoals = ($goals - (3*$ownGoals));
                if($combinedGoals>0){
                    $goalsRatio = $combinedGoals/$gamesPlayed;
                }else{
                    $goalsRatio = 0;
                }

                $teamSpiritRatio = ($teamSpirit)/2;

                $teamPlayerRatio = (
                        ($gamesPlayedRatio*5)+
                        ($teamPointsRatio*5)+
                        ($potmTitlesRatio*4)+
                        ($potmNominationsRatio*3)+
                        ($assistsRatio*2)+
                        ($goalsRatio)+
                        ($teamSpiritRatio)
                    )/20.5;

                $teamPlayerRating = ($teamPlayerRatio*$gamesPlayed)/$totalGames;

                $playerRatio = (
                        ($potmTitlesRatio*6)+
                        ($potmNominationsRatio*4)+
                        ($assistsRatio*3)+
                        ($goalsRatio*3)
                    )/21;

                $playerRating = $playerRatio;

                $wdl_winPercent = $gamesWon/$gamesPlayed;
                $wdl_drawPercent = $gamesDraw/$gamesPlayed;
                $wdl_losePercent = $gamesLost/$gamesPlayed;

                if($format) {
                    $result = [
                        'teamPlayerRating' => number_format((float)($teamPlayerRating * 100), 2, '.', '') . '%',
                        'playerRating' => number_format((float)($playerRating * 100), 2, '.', '') . '%',
                        'teamPointsRating' => number_format((float)($teamPointsRatio * 100), 2, '.', '') . '%',
                        'potmRating' => number_format((float)($potmTitlesRatio * 100), 2, '.', '') . '%',
                        'potmnRating' => number_format((float)($potmNominationsRatio * 100), 2, '.', '') . '%',
                        'assistsRating' => number_format((float)($assistsRatio), 2, '.', '') . '',
                        'goalsRating' => number_format((float)$goalsRatio, 2, '.', '') . '',
                        'wdlWin' => number_format((float)($wdl_winPercent * 100), 2, '.', '') . '%',
                        'wdlDraw' => number_format((float)($wdl_drawPercent * 100), 2, '.', '') . '%',
                        'wdlLost' => number_format((float)($wdl_losePercent * 100), 2, '.', '') . '%'
                    ];
                }else{
                    $result = [
                        'teamPlayerRating' => $teamPlayerRating,
                        'playerRating' => $playerRating,
                        'teamPointsRating' => $teamPointsRatio,
                        'potmRating' => $potmTitlesRatio,
                        'potmnRating' => $potmNominationsRatio,
                        'assistsRating' => $assistsRatio,
                        'goalsRating' => $goalsRatio,
                        'wdlWin' => $wdl_winPercent,
                        'wdlDraw' => $wdl_drawPercent,
                        'wdlLost' => $wdl_losePercent
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function gamesIds()
    {
        $teams = $this->teams()->getResults();

        $gameIds = [];
        foreach ($teams as $team)
        {
            $gameIds[] = $team->game_id;
        }

        return $gameIds;
    }

    /**
     * @return array
     */
    public function playerGameStats()
    {
        $gameIds = $this->gamesIds();
        $gameStats = [];

        foreach ($gameIds as $gameId) {
            $gameStats[$gameId] = [
                'game' => Game::find($gameId),
                'stats' => $this->calculateStats([$this->getStatsForGame($gameId)])
            ];
        }

        return $gameStats;
    }

    /**
     * @param $gameId
     * @return bool
     */
    public function hasPlayedInGame($gameId)
    {
        $team = $this->teams()->where('game_id', '=', $gameId)->first();

        return $team ? true : false;
    }

    /**
     * @param $gameId
     * @return bool
     */
    public function hasStatsForGame($gameId)
    {
        $team = $this->teams()->where('game_id', '=', $gameId)->first();
        if($team){
            $stats = $this->stats()->where('team_id', '=', $team->id)->count();
        }else{
            $stats = 0;
        }

        return $stats>0;
    }

    /**
     * @param $gameId
     * @return array
     */
    public function getStatsForGame($gameId)
    {
        $team = $this->teams()->where('game_id', '=', $gameId)->first();
        if($team!=null) {
            $stats = $this->stats()->where('team_id', '=', $team->id)->first();
            return $stats;
        }else{
            return null;
        }
    }

    /**
     * @param $gameId
     * @return bool
     */
    public function isCaptainForGame($gameId)
    {
        $stats = $this->getStatsForGame($gameId);

        if($stats){
            return $stats->is_captain;
        }else{
            return false;
        }
    }

    /**
     * @param bool|false $short
     * @return string
     */
    public function formalName($short=false)
    {
        if($short){
            return strtoupper(substr($this->first_name, 0, 1)) . ". " . ucfirst($this->last_name);
        }else{
            return ucfirst($this->first_name) . " " . ucfirst($this->last_name);
        }
    }

    /**
     * @param $stats
     * @param null $seasonId
     * @return array
     */
    public function calculateStats($stats, $seasonId = null)
    {
        $computedStats = [
            'team_points' => 0,
            'potm' => 0,
            'potmn' => 0,
            'nomination_points' => 0,
            'team_spirit_points' => 0,
            'goals' => 0,
            'own_goals' => 0,
            'assists' => 0,
            'games_played' => 0,
            'excuses' => 0,
            'rating' => [],

        ];

        if(is_array($stats) || get_class($stats)=='Illuminate\Database\Eloquent\Collection') {
            foreach ($stats as $stat) {
                if ($stat) {
                    $computedStats['games_played'] += 1;
                    $computedStats['team_points'] += $stat->team_points;
                    $computedStats['potm'] += ($stat->player_of_the_match ? 1 : 0);
                    $computedStats['potmn'] += ($stat->player_of_the_match_nomination ? 1 : 0);
                    $computedStats['nomination_points'] += $stat->player_of_the_match_nomination_points;
                    $computedStats['team_spirit_points'] += $stat->team_spirit_points;
                }
            }
        }else{
            if ($stats) {
                $computedStats['games_played'] += 1;
                $computedStats['team_points'] += $stats->team_points;
                $computedStats['potm'] += ($stats->player_of_the_match ? 1 : 0);
                $computedStats['potmn'] += ($stats->player_of_the_match_nomination ? 1 : 0);
                $computedStats['nomination_points'] += $stats->player_of_the_match_nomination_points;
                $computedStats['team_spirit_points'] += $stats->team_spirit_points;
            }
        }

        $computedStats['goals'] = $this->getComputedPlayerGoals($stats)['goals'];
        $computedStats['own_goals'] = $this->getComputedPlayerGoals($stats)['ownGoals'];
        $computedStats['assists'] = $this->getComputedPlayerAssists($stats);

        if ($seasonId) {
            $computedStats['excuses'] = $this->getExcusesForSeason($seasonId)->count();
            $computedStats['rating'] = $this->computeRating($seasonId);
        } else {
            $computedStats['excuses'] = $this->excuses()->count();
            $computedStats['rating'] = $this->computeRating();
        }

        return $computedStats;
    }

    /**
     * @param $stats
     * @return array
     */
    public function getComputedPlayerGoals($stats)
    {
        $totalGoals = [
            'goals' => 0,
            'ownGoals' => 0
        ];

        if(is_array($stats) || get_class($stats)=='Illuminate\Database\Eloquent\Collection') {
            $statIds = [];
            foreach ($stats as $stat) {
                if ($stat) {
                    $statIds[] = $stat->id;
                }
            }

            $goals = Goal::whereIn('stat_id',$statIds)->get();

            foreach ($goals as $goal) {
                if ($goal->own_goal) {
                    $totalGoals['ownGoals'] += 1;
                } else {
                    $totalGoals['goals'] += 1;
                }
            }
        }else{
            if ($stats) {
                $goals = $stats->goals()->getResults();

                foreach ($goals as $goal) {
                    if ($goal->own_goal) {
                        $totalGoals['ownGoals'] += 1;
                    } else {
                        $totalGoals['goals'] += 1;
                    }
                }
            }
        }


        return $totalGoals;
    }

    /**
     * @param $stats
     * @return int
     */
    public function getComputedPlayerAssists($stats)
    {
        $playerAssists = 0;
        if(is_array($stats) || get_class($stats)=='Illuminate\Database\Eloquent\Collection') {
            $statIds = [];
            foreach ($stats as $stat) {
                if ($stat) {
                    $statIds[] = $stat->id;
                }
            }

            $assists = Assist::whereIn('stat_id', $statIds)->count();
            $playerAssists += $assists;
        }else{
            if ($stats) {
                $assists = $stats->assists()->count();
                $playerAssists += $assists;
            }
        }

        return $playerAssists;
    }

    /**
     * @return string
     */
    public function getImage(){
        if(file_exists(public_path()."/assets/player_avatars/".$this->id.".jpg")) {
            return "/assets/player_avatars/".$this->id.".jpg";
        }
        else {
            return "/assets/player_avatars/default.jpg";
        }
    }

    /**
     * This function queries the database and finds the number of votes a person has for a certain place
     * @param int $gameId
     * @param int $place 1,2,3,4
     * @return mixed
     */
    public function countPlayerNominationsForGame($gameId, $place)
    {
        return POTMVote::where('game_id','=',$gameId)
            ->where('player'.$place.'_id','=',$this->id)
            ->count();
    }

    /**
     * This function computes the nomination points for a player
     * @param int $gameId
     * @return mixed
     */
    public function computeNominationPoints($gameId)
    {
        $total = 0;
        $multipliers = [
            1 => 4,
            2 => 3,
            3 => 2,
            4 => 1
        ];

        for($i=1;$i<5;$i++){

            $total += $this->countPlayerNominationsForGame($gameId, $i)*$multipliers[$i];
        }

        return $total;
    }
}