<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackType extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function playedTrack(): HasMany
    {
        return $this->hasMany(PlayedTrack::class);
    }
}
