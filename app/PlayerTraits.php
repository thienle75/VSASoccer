<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class PlayerTraits extends Model
{
    use RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'player_traits';

    protected $fillable = [
        'player_id',
        'trait_id'
    ];
}
