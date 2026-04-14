<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $url
 * @property string $imageUrl
 */
class Composer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'url',
        'image',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ComposerTranslation::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function translation(string $languageCode = 'sr'): ?ComposerTranslation
    {
        return $this->translations()->whereHas('language', function ($query) use ($languageCode) {
            $query->where('code', $languageCode);
        })->first();
    }
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return \Illuminate\Support\Facades\Storage::disk('composer-images')->url($this->image);
    }
}
