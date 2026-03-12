<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'content',
        'published_at',
        'image'
    ];

    protected $attributes = [
        'title' => '',
        'content' => '',
        'slug' => '',
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
}
