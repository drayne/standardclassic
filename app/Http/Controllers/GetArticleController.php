<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetArticleController extends Controller
{
    public function __invoke(Request $request, string $slug)
    {
        $article = \App\Models\Article::where('slug', $slug)
            ->with(['translations' => function ($query) {
                $query->whereHas('language', function ($q) {
                    $q->where('code', app()->getLocale());
                });
            }])
            ->where('active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return \Inertia\Inertia::render('Articles/Show', [
            'article' => new \App\Http\Resources\ArticleResource($article),
        ]);
    }
}
