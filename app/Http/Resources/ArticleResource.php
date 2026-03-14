<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\ArticleTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* @var Article $this */
        $translation = $this->translations->first();

        return [
            'id' => $this->id,
            'title' => $translation?->title,
            'content' => $translation?->content,
            'image' => $this->image_url,
            'published_at' => $this->published_at?->format('d.m.Y'),
        ];
    }
}
