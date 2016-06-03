<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Venturecraft\Revisionable\RevisionableTrait;

class AwardType extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'award_types';

    protected $fillable = [
        'name',
        'description',
        'badge'
    ];

    /**
     * Grabs the awardType
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function awards()
    {
        return $this->hasMany('App\Awards');
    }
}
