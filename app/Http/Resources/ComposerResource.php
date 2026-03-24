<?php

namespace App\Http\Resources;

use App\Models\Composer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComposerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();

        $translation = $this->translations->firstWhere('language.code', $lang)
            ?? $this->translations->firstWhere('language.code', 'sr');

        /** @var Composer $this */
        return [
            'name'        => $this->name,
            'description' => $translation?->description,
            'url'         => $this->url,
            'image'       => $this->imageUrl,
        ];
    }
}
