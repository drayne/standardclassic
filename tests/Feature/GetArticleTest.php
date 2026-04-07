<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GetArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_single_article(): void
    {
        $category = Category::where('slug', 'vijesti-iz-kulture')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Vijesti iz kulture',
                'slug' => 'vijesti-iz-kulture',
            ]);
        }

        $language = Language::where('code', 'sr')->first();
        if (!$language) {
            $language = Language::create(['name' => 'Srpski', 'code' => 'sr']);
        }

        $article = Article::create([
            'category_id' => $category->id,
            'slug' => 'test-vijest',
            'active' => true,
            'published_at' => now(),
        ]);

        $article->translations()->create([
            'language_id' => $language->id,
            'title' => 'Test Naslov',
            'content' => 'Test Sadržaj',
        ]);

        $slug = $article->fresh()->slug;

        $this->app->setLocale('sr');
        $response = $this->get(route('vijest', ['slug' => $slug]));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Articles/Show')
            ->has('article', fn (Assert $page) => $page
                ->where('title', 'Test Naslov')
                ->where('content', 'Test Sadržaj')
                ->where('slug', $slug)
                ->where('published_at', $article->published_at->translatedFormat('d. F Y.'))
                ->etc()
            )
        );
    }

    public function test_cannot_view_inactive_article(): void
    {
        $category = Category::where('slug', 'vijesti-iz-kulture')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Vijesti iz kulture',
                'slug' => 'vijesti-iz-kulture',
            ]);
        }

        Article::create([
            'category_id' => $category->id,
            'slug' => 'inactive-vijest',
            'active' => false,
            'published_at' => now(),
        ]);

        $response = $this->get(route('vijest', ['slug' => 'inactive-vijest']));

        $response->assertStatus(404);
    }

    public function test_cannot_view_future_article(): void
    {
        $category = Category::where('slug', 'vijesti-iz-kulture')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Vijesti iz kulture',
                'slug' => 'vijesti-iz-kulture',
            ]);
        }

        Article::create([
            'category_id' => $category->id,
            'slug' => 'future-vijest',
            'active' => true,
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get(route('vijest', ['slug' => 'future-vijest']));

        $response->assertStatus(404);
    }
}
