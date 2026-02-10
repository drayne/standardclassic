<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
