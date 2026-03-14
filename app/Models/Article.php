<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $image
 * @property bool $active
 * @property DateTime published_at
 */
class Article extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'published_at',
        'image',
        'active',
    ];

    protected $attributes = [
        'slug' => '',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('article-images')->url($this->image) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function translation(string $languageCode = 'sr')
    {
        return $this->translations()->whereHas('language', function ($query) use ($languageCode) {
            $query->where('code', $languageCode);
        })->first();
    }


}
