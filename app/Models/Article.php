<?php

namespace App\Models;

use App\Services\ImageOptimizer;
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

    /**
     * Izmjene slika iz trenutnog snimanja (popunjava se u "saving", koristi u "saved").
     */
    private ?array $pendingImageChanges = null;

    protected static function booted(): void
    {
        // Izmjene slika bilježimo prije snimanja, jer refresh() (ovdje i u LogsActivity) briše originalne vrijednosti
        static::saving(function (Article $article) {
            $article->pendingImageChanges = [
                'image' => $article->isDirty('image'),
                'previousImage' => $article->exists ? $article->getOriginal('image') : null,
                'gallery' => $article->isDirty('gallery'),
                'previousGallery' => $article->exists ? ($article->getOriginal('gallery') ?? []) : [],
            ];
        });

        static::saved(function (Article $article) {
            $changes = $article->pendingImageChanges;
            $article->pendingImageChanges = null;

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

                        // Thumbnail prati preimenovanu sliku
                        $oldThumbnail = ImageOptimizer::thumbnailPath($article->image);
                        if ($disk->exists($oldThumbnail)) {
                            $disk->delete(ImageOptimizer::thumbnailPath($newName));
                            $disk->move($oldThumbnail, ImageOptimizer::thumbnailPath($newName));
                        }

                        $article->withoutEvents(function () use ($article, $newName) {
                            $article->updateQuietly(['image' => $newName]);
                        });
                    }
                }
            }

            if ($changes) {
                $article->optimizeImages($changes['image'], $changes['previousImage'], $changes['gallery'], $changes['previousGallery']);
            }
        });

        static::deleted(function (Article $article) {
            $disk = Storage::disk('article-images');

            collect([$article->image, ...($article->gallery ?? [])])
                ->filter()
                ->each(fn (string $path) => $disk->delete(ImageOptimizer::thumbnailPath($path)));
        });
    }

    /**
     * Smanjuje nove slike i pravi im thumbnails; uklanja thumbnails slika koje više nisu u vijesti.
     */
    private function optimizeImages(bool $imageChanged, ?string $previousImage, bool $galleryChanged, array $previousGallery): void
    {
        $disk = Storage::disk('article-images');
        $optimizer = app(ImageOptimizer::class);

        $newPaths = [];
        $removedPaths = [];

        if ($imageChanged) {
            $newPaths[] = $this->image;
            $removedPaths[] = $previousImage;
        }

        if ($galleryChanged) {
            $gallery = $this->gallery ?? [];
            $newPaths = [...$newPaths, ...array_diff($gallery, $previousGallery)];
            $removedPaths = [...$removedPaths, ...array_diff($previousGallery, $gallery)];
        }

        foreach (array_filter($removedPaths) as $path) {
            if (! str_starts_with($path, 'http')) {
                $disk->delete(ImageOptimizer::thumbnailPath($path));
            }
        }

        // PNG slike se mogu pretvoriti u JPEG, pa pamtimo nove putanje
        $renamed = [];
        foreach (array_filter($newPaths) as $path) {
            $optimizedPath = $optimizer->optimize($disk, $path);
            $optimizer->createThumbnail($disk, $optimizedPath);

            if ($optimizedPath !== $path) {
                $renamed[$path] = $optimizedPath;
            }
        }

        if ($renamed) {
            $this->updateQuietly([
                'image' => $renamed[$this->image] ?? $this->image,
                'gallery' => $this->gallery
                    ? array_map(fn (string $path) => $renamed[$path] ?? $path, $this->gallery)
                    : $this->gallery,
            ]);
        }
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

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->image ? $this->imageUrls($this->image)['thumbnail'] : null;
    }

    /**
     * Sve slike vijesti (naslovna prva, zatim galerija), svaka sa URL-om originala i thumbnaila.
     *
     * @return array<int, array{url: string, thumbnail: string}>
     */
    public function getGalleryImagesAttribute(): array
    {
        return collect([$this->image, ...($this->gallery ?? [])])
            ->filter()
            ->unique()
            ->map(fn (string $path) => $this->imageUrls($path))
            ->values()
            ->all();
    }

    /**
     * Za slike bez thumbnaila (npr. one uploadovane prije optimizacije) koristimo original.
     *
     * @return array{url: string, thumbnail: string}
     */
    private function imageUrls(string $path): array
    {
        if (str_starts_with($path, 'http')) {
            return ['url' => $path, 'thumbnail' => $path];
        }

        $disk = Storage::disk('article-images');
        $thumbnailPath = ImageOptimizer::thumbnailPath($path);

        return [
            'url' => $disk->url($path),
            'thumbnail' => $disk->url($disk->exists($thumbnailPath) ? $thumbnailPath : $path),
        ];
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
