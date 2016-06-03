<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POTMVote extends Model
{
    protected $table = 'potm';

    protected $fillable = [
        'game_id',
        'player_id',
        'player1_id',
        'player2_id',
        'player3_id',
        'player4_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Player');
    }
}
