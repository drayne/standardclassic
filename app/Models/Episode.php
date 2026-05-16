<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    protected $fillable = [
        'show_id',
        'podcast_id',
        'media_id',
        'external_url',
        'title',
        'description',
        'datum',
    ];

    protected $casts = [
        'datum' => 'date',
    ];

    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
