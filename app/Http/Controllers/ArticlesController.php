<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticlesController extends Controller
{
    public function __invoke(Request $request)
    {
        $categorySlug = $request->route()->getName();

        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $articles = $category->articles()
            ->with(['translations' => function ($query) {
                $query->whereHas('language', function ($q) {
                    $q->where('code', app()->getLocale());
                });
            }])
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(1, ['*'], 's')
            ->onEachSide(1);

        return Inertia::render('Articles/Index', [
            'articles' => ArticleResource::collection($articles),
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
        ]);
    }
}
