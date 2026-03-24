<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComposerTranslation extends Model
{
    protected $fillable = [
        'composer_id',
        'language_id',
        'description',
    ];

    public function composer(): BelongsTo
    {
        return $this->belongsTo(Composer::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
