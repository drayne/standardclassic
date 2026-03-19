<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CoverImageResource;
use App\Models\CoverImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct(
        protected ArticleRepository $articleRepository
    ) {}

    public function __invoke()
    {
        $kulturaArticles = $this->articleRepository->getLatestKulturaArticles();
        $dpArticles = $this->articleRepository->getLatestDpArticles();
        $coverImages = CoverImage::whereIn('position', ['L', 'R'])->get();

        return Inertia::render('Index', [
            'kulturaArticles' => ArticleResource::collection($kulturaArticles),
            'dpArticles' => ArticleResource::collection($dpArticles),
            'coverImages' => CoverImageResource::collection($coverImages),
        ]);
    }
}
