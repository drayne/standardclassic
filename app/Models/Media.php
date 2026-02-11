<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'media_type_id',
        'title',
        'artist',
        'file_path',
        'image_path',
        'duration',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }

    public function playlists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_media')
            ->withPivot('sort_order');
    }

    protected static function booted(): void
    {
        static::deleted(function ($media) {
            // Brišemo audio fajl
            if ($media->file_path) {
                Storage::disk('radio')->delete($media->file_path);
            }

            // Brišemo cover sliku
            if ($media->image_path) {
                Storage::disk('radio-covers')->delete($media->image_path);
            }
        });
    }
}
