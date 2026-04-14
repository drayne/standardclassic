<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaSchedule extends Model
{
    use LogsActivity;

    protected    $fillable = [
        'media_id',
        'scheduled_at',
        'played',
    ];

    public function getDisplayName(): string
    {
        return "zakazano puštanje za: " . ($this->media?->title ?? $this->id);
    }

    protected $casts = [
        'scheduled_at' => 'datetime',
        'played' => 'boolean',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
