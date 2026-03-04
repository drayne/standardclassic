<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayedTrack extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'media_id',
        'track_type_id',
        'played_at'
    ];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TrackType::class);
    }
}
