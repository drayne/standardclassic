<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    protected $fillable = ['name', 'active'];

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'playlist_media')
            ->withPivot('sort_order')
            ->orderBy('playlist_media.sort_order');
    }

    protected function casts(): array
    {
        return [
            'active'
        ];
    }
}
