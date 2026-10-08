<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    public function test_article_exposes_main_image_and_gallery_images(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'vijesti-iz-kulture'],
            ['name' => 'Vijesti iz kulture'],
        );

        $article = Article::create([
            'category_id' => $category->id,
            'slug' => 'vijest-sa-galerijom',
            'active' => true,
            'published_at' => now(),
            'image' => 'https://example.com/glavna.jpg',
            'gallery' => ['https://example.com/druga.jpg', '2026/10/treca.jpg'],
        ]);

        $response = $this->get(route('vijest', ['slug' => $article->slug]));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Articles/Show')
            ->where('article.image', 'https://example.com/glavna.jpg')
            ->where('article.images', [
                'https://example.com/glavna.jpg',
                'https://example.com/druga.jpg',
                Storage::disk('article-images')->url('2026/10/treca.jpg'),
            ])
        );
    }

    public function test_article_without_gallery_has_only_main_image(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'vijesti-iz-kulture'],
            ['name' => 'Vijesti iz kulture'],
        );

        $article = Article::create([
            'category_id' => $category->id,
            'slug' => 'vijest-bez-galerije',
            'active' => true,
            'published_at' => now(),
            'image' => 'https://example.com/glavna.jpg',
        ]);

        $response = $this->get(route('vijest', ['slug' => $article->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('article.images', ['https://example.com/glavna.jpg'])
        );
    }
}
