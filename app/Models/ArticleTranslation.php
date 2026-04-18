<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ArticleTranslation extends Model
{
    protected $fillable = [
        'article_id',
        'language_id',
        'title',
        'content'
    ];

    protected static function booted(): void
    {
        static::saved(function ($translation) {
            // Slug se generiše isključivo iz naslova na srpskom jeziku ('sr')
            $language = $translation->language;

            if ($language && $language->code === 'sr') {
                $baseSlug = Str::slug($translation->title);
                $slug = $baseSlug;
                $counter = 1;

                // Provera jedinstvenosti sluga
                while (Article::where('slug', $slug)
                    ->where('id', '!=', $translation->article_id)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $translation->article->slug = $slug;
                $translation->article->save();
            }
        });
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
