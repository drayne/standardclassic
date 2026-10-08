<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Language;
use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleImageOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! function_exists('imagecreatefromstring')) {
            $this->markTestSkipped('GD ekstenzija nije instalirana.');
        }

        Storage::fake('article-images');
    }

    private function storeImage(string $path, int $width, int $height): string
    {
        Storage::disk('article-images')->put($path, UploadedFile::fake()->image('slika.jpg', $width, $height)->getContent());

        return $path;
    }

    private function category(): Category
    {
        return Category::firstOrCreate(
            ['slug' => 'vijesti-iz-kulture'],
            ['name' => 'Vijesti iz kulture'],
        );
    }

    public function test_main_image_is_resized_and_gets_thumbnail(): void
    {
        $this->storeImage('2026/10/velika.jpg', 3000, 2000);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'velika-slika',
            'image' => '2026/10/velika.jpg',
        ]);

        $disk = Storage::disk('article-images');
        $image = $article->fresh()->image;

        [$width, $height] = getimagesizefromstring($disk->get($image));
        $this->assertSame(ImageOptimizer::MAX_WIDTH, $width);
        $this->assertSame(1280, $height);

        $thumbnailPath = ImageOptimizer::thumbnailPath($image);
        $disk->assertExists($thumbnailPath);
        [$thumbnailWidth] = getimagesizefromstring($disk->get($thumbnailPath));
        $this->assertSame(ImageOptimizer::THUMBNAIL_WIDTH, $thumbnailWidth);

        $this->assertSame($disk->url($thumbnailPath), $article->fresh()->thumbnail_url);
    }

    public function test_small_image_is_not_upscaled(): void
    {
        $this->storeImage('2026/10/mala.jpg', 400, 300);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'mala-slika',
            'image' => '2026/10/mala.jpg',
        ]);

        $disk = Storage::disk('article-images');
        [$thumbnailWidth] = getimagesizefromstring($disk->get(ImageOptimizer::thumbnailPath($article->fresh()->image)));
        $this->assertSame(400, $thumbnailWidth);
    }

    public function test_thumbnail_follows_image_when_slug_changes(): void
    {
        $this->storeImage('2026/10/slika.jpg', 1000, 800);
        $language = Language::firstOrCreate(['code' => 'sr'], ['name' => 'Srpski']);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'image' => '2026/10/slika.jpg',
        ]);

        $article->translations()->create([
            'language_id' => $language->id,
            'title' => 'Nova vijest',
            'content' => 'Sadržaj',
        ]);

        $image = $article->fresh()->image;
        $this->assertSame("2026/10/nova-vijest-{$article->id}.jpg", $image);
        Storage::disk('article-images')->assertExists(ImageOptimizer::thumbnailPath($image));
        Storage::disk('article-images')->assertMissing(ImageOptimizer::thumbnailPath("2026/10/article-{$article->id}.jpg"));
    }

    public function test_gallery_images_get_thumbnails_and_removed_ones_are_cleaned_up(): void
    {
        $this->storeImage('2026/10/prva.jpg', 2500, 1500);
        $this->storeImage('2026/10/druga.jpg', 800, 600);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'galerija',
            'gallery' => ['2026/10/prva.jpg', '2026/10/druga.jpg'],
        ]);

        $disk = Storage::disk('article-images');
        [$width] = getimagesizefromstring($disk->get('2026/10/prva.jpg'));
        $this->assertSame(ImageOptimizer::MAX_WIDTH, $width);
        $disk->assertExists(ImageOptimizer::thumbnailPath('2026/10/prva.jpg'));
        $disk->assertExists(ImageOptimizer::thumbnailPath('2026/10/druga.jpg'));

        $article->update(['gallery' => ['2026/10/druga.jpg']]);

        $disk->assertMissing(ImageOptimizer::thumbnailPath('2026/10/prva.jpg'));
        $disk->assertExists(ImageOptimizer::thumbnailPath('2026/10/druga.jpg'));
    }

    public function test_existing_image_without_thumbnail_falls_back_to_original(): void
    {
        $this->storeImage('2026/01/stara.jpg', 3000, 2000);

        $article = Article::withoutEvents(fn () => Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'stara-vijest',
            'image' => '2026/01/stara.jpg',
        ]));

        $disk = Storage::disk('article-images');
        $this->assertSame($disk->url('2026/01/stara.jpg'), $article->thumbnail_url);

        // Postojeće slike se ne diraju
        [$width] = getimagesizefromstring($disk->get('2026/01/stara.jpg'));
        $this->assertSame(3000, $width);
    }

    private function storePng(string $path, int $width, int $height, bool $transparent = false): string
    {
        $image = imagecreatetruecolor($width, $height);
        imagesavealpha($image, true);
        imagealphablending($image, false);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 200, 100, 50, $transparent ? 127 : 0));

        ob_start();
        imagepng($image);
        Storage::disk('article-images')->put($path, ob_get_clean());

        return $path;
    }

    public function test_opaque_png_is_converted_to_jpeg(): void
    {
        $this->storePng('2026/10/foto.png', 2500, 1500);
        $this->storePng('2026/10/galerija.png', 800, 600);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'png-vijest',
            'image' => '2026/10/foto.png',
            'gallery' => ['2026/10/galerija.png'],
        ]);

        $article = $article->fresh();
        $disk = Storage::disk('article-images');

        $this->assertSame("2026/10/png-vijest-{$article->id}.jpg", $article->image);
        $this->assertSame(['2026/10/galerija.jpg'], $article->gallery);

        foreach ([$article->image, '2026/10/galerija.jpg'] as $path) {
            $this->assertSame(IMAGETYPE_JPEG, getimagesizefromstring($disk->get($path))[2]);
            $disk->assertExists(ImageOptimizer::thumbnailPath($path));
        }

        [$width] = getimagesizefromstring($disk->get($article->image));
        $this->assertSame(ImageOptimizer::MAX_WIDTH, $width);
        $disk->assertMissing("2026/10/png-vijest-{$article->id}.png");
        $disk->assertMissing('2026/10/galerija.png');
    }

    public function test_transparent_png_stays_png(): void
    {
        $this->storePng('2026/10/logo.png', 800, 600, transparent: true);

        $article = Article::create([
            'category_id' => $this->category()->id,
            'slug' => 'logo',
            'gallery' => ['2026/10/logo.png'],
        ]);

        $this->assertSame(['2026/10/logo.png'], $article->fresh()->gallery);
        Storage::disk('article-images')->assertExists(ImageOptimizer::thumbnailPath('2026/10/logo.png'));
    }
}
