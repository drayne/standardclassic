<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'active'];

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'playlist_media')
            ->using(PlaylistMedia::class)
            ->withPivot(['id', 'sort_order'])
            ->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'active'
        ];
    }
}
