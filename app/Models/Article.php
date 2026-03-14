<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    protected static function booted(): void
    {
        static::saved(function (Article $article) {
            if ($article->image && !str_starts_with($article->image, 'http')) {
                $extension = pathinfo($article->image, PATHINFO_EXTENSION);

                // Osiguravamo da imamo najsvježiji slug (ako je postavljen u ArticleTranslation)
                $article->refresh();

                $slug = $article->slug ?: 'article-' . $article->id;
                $newName = "{$slug}-{$article->id}.{$extension}";

                if ($article->image !== $newName) {
                    $disk = Storage::disk('article-images');
                    if ($disk->exists($article->image)) {
                        if ($disk->exists($newName)) {
                            $disk->delete($newName);
                        }

                        $disk->move($article->image, $newName);

                        $article->withoutEvents(function () use ($article, $newName) {
                            $article->updateQuietly(['image' => $newName]);
                        });
                    }
                }
            }
        });
    }

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
        if (!$this->image) {
            return null;
        }

        // If it starts with http, it's already a full URL (e.g. from a seeder)
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::disk('article-images')->url($this->image);
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
