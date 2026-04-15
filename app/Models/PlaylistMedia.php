<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistMedia extends Pivot
{
    protected $table = 'playlist_media';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'playlist_id',
        'media_id',
        'sort_order',
    ];
}
