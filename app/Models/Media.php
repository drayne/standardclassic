<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use LogsActivity;

    protected $fillable = [
        'media_type_id',
        'composer_id',
        'title',
        'artist',
        'file_path',
        'duration',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }

    public function composer(): BelongsTo
    {
        return $this->belongsTo(Composer::class);
    }

    public function playlists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_media')
            ->using(PlaylistMedia::class)
            ->withPivot(['id', 'sort_order']);
    }

    protected static function booted(): void
    {
        static::deleted(function ($media) {
            // Brišemo audio fajl
            if ($media->file_path) {
                Storage::disk('radio')->delete($media->file_path);
            }
        });
    }
}
