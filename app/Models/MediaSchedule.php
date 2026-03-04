<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaSchedule extends Model
{
    protected    $fillable = [
        'media_id',
        'scheduled_at',
        'played',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'played' => 'boolean',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
