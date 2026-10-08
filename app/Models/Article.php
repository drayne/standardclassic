<?php

namespace App\Models;

use App\Traits\LogsActivity;
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
 * @property array|null $gallery
 * @property string $image_source
 * @property string $article_source
 * @property bool $active
 * @property DateTime published_at
 */
class Article extends Model
{
    use LogsActivity;

    protected static function booted(): void
    {
        static::saved(function (Article $article) {
            if ($article->image && !str_starts_with($article->image, 'http')) {
                $pathInfo = pathinfo($article->image);
                $extension = $pathInfo['extension'] ?? '';
                $directory = ($pathInfo['dirname'] === '.') ? '' : $pathInfo['dirname'] . '/';

                // Osiguravamo da imamo najsvježiji slug (ako je postavljen u ArticleTranslation)
                $article->refresh();

                $slug = $article->slug ?: 'article-' . $article->id;
                $newName = "{$directory}{$slug}-{$article->id}.{$extension}";

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
        'gallery',
        'image_source',
        'article_source',
        'active',
    ];

    protected $attributes = [
        'slug' => '',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'active' => 'boolean',
        'gallery' => 'array',
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

    /**
     * URL-ovi svih slika vijesti: glavna slika prva, zatim slike iz galerije.
     *
     * @return array<int, string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        $disk = Storage::disk('article-images');

        $galleryUrls = collect($this->gallery ?? [])
            ->filter()
            ->map(fn (string $path) => str_starts_with($path, 'http') ? $path : $disk->url($path));

        return collect([$this->image_url])
            ->merge($galleryUrls)
            ->filter()
            ->unique()
            ->values()
            ->all();
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

    public function getDisplayName()
    {
        return $this->translation('sr')?->title ?? $this->slug;
    }

}
