<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class Award extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'awards';

    protected $fillable = [
        'award_type_id',
        'player_id',
        'season_id'
    ];

    /**
     * Grabs the awardType
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function award_type()
    {
        return $this->belongsTo('App\AwardType');
    }

    /**
     * Grabs the related player
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Player');
    }

    /**
     * Grabs the related season object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    /**
     * returns the url to the badge image
     * @return string
     */
    public function getBadge()
    {
        $awardType = $this->award_type()->getResults();
        $badge = $awardType->badge;

        return url('assets/awards').'/'.$badge;
    }
}
