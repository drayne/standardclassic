<?php

namespace App\Repositories;

use App\Enums\Category as CategoryEnum;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    /**
     * Get the last 6 articles from the "Kultura" category,
     * translated into the current application locale.
     *
     * @return Collection
     */
    public function getLatestKulturaArticles(): Collection
    {
        return $this->getByCategory(CategoryEnum::KULTURA, 2);
    }

    public function getLatestDpArticles(): Collection
    {
        return $this->getByCategory(CategoryEnum::DNEVNOPOLITICKE, 6);
    }

    private function getByCategory (CategoryEnum $category, int $count = 2): Collection
    {
        $categoryId = Category::where('slug', $category->value)->value('id');

        return Article::with(['translations' => function ($query) {
            $query->whereHas('language', function ($q) {
                $q->where('code', app()->getLocale());
            });
        }, 'category'])
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('category_id', $categoryId)
            ->where('active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take($count)
            ->get();
    }
}
