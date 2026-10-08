<?php

namespace App\Services;

use GdImage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

/**
 * Smanjuje i kompresuje uploadovane slike i pravi manje verzije (thumbnails).
 * PNG slike bez providnosti se pretvaraju u JPEG, jer PNG kompresija fotografije gotovo ne smanjuje.
 */
class ImageOptimizer
{
    public const MAX_WIDTH = 1920;

    public const THUMBNAIL_WIDTH = 600;

    public const QUALITY = 80;

    public const THUMBNAIL_DIRECTORY = 'thumbs';

    // Manje JPEG/WebP slike od ove veličine se ne kompresuju ponovo, da ne gube na kvalitetu
    private const SKIP_BELOW_BYTES = 400 * 1024;

    public static function thumbnailPath(string $path): string
    {
        return self::THUMBNAIL_DIRECTORY . '/' . ltrim($path, '/');
    }

    /**
     * Smanjuje sliku tako da nijedna strana nije veća od MAX_WIDTH piksela i ponovo je kompresuje.
     * Vraća putanju optimizovane slike, koja se razlikuje od ulazne ako je PNG pretvoren u JPEG.
     */
    public function optimize(Filesystem $disk, string $path, int $maxWidth = self::MAX_WIDTH): string
    {
        if (! $this->canProcess($disk, $path)) {
            return $path;
        }

        $contents = $disk->get($path);
        $image = $this->load($contents, $path);

        if ($image === null) {
            return $path;
        }

        $format = $this->format($path);

        if ($format === 'png' && ! $this->hasTransparency($image)) {
            $jpegPath = $this->uniqueJpegPath($disk, $path);
            $disk->put($jpegPath, $this->encode($this->resize($image, $maxWidth, $maxWidth), 'jpeg'));
            $disk->delete($path);

            return $jpegPath;
        }

        $needsRotation = $this->exifOrientation($contents, $path) > 1;
        $needsResize = max(imagesx($image), imagesy($image)) > $maxWidth;

        if (! $needsResize && ! $needsRotation && ($format === 'png' || strlen($contents) <= self::SKIP_BELOW_BYTES)) {
            return $path;
        }

        $optimized = $this->encode($this->resize($image, $maxWidth, $maxWidth), $format);

        // Zadržavamo original ako novi fajl nije manji (osim ako je slika smanjena ili okrenuta)
        if ($optimized !== null && ($needsResize || $needsRotation || strlen($optimized) < strlen($contents))) {
            $disk->put($path, $optimized);
        }

        return $path;
    }

    /**
     * Pravi manju verziju slike u folderu THUMBNAIL_DIRECTORY i vraća njenu putanju.
     */
    public function createThumbnail(Filesystem $disk, string $path, int $width = self::THUMBNAIL_WIDTH): ?string
    {
        if (! $this->canProcess($disk, $path)) {
            return null;
        }

        $image = $this->load($disk->get($path), $path);
        $thumbnail = $image ? $this->encode($this->resize($image, $width), $this->format($path)) : null;

        if ($thumbnail === null) {
            return null;
        }

        $thumbnailPath = self::thumbnailPath($path);
        $disk->put($thumbnailPath, $thumbnail);

        return $thumbnailPath;
    }

    private function canProcess(Filesystem $disk, string $path): bool
    {
        if (str_starts_with($path, 'http') || ! $disk->exists($path)) {
            return false;
        }

        if (! function_exists('imagecreatefromstring')) {
            Log::warning('ImageOptimizer: GD ekstenzija nije instalirana, slika nije optimizovana.', ['path' => $path]);

            return false;
        }

        return $this->format($path) !== null;
    }

    private function format(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // GIF preskačemo da ne bismo pokvarili animacije
        return match ($extension) {
            'jpg', 'jpeg' => 'jpeg',
            'png' => 'png',
            'webp' => 'webp',
            default => null,
        };
    }

    private function uniqueJpegPath(Filesystem $disk, string $path): string
    {
        $base = preg_replace('/\.png$/i', '', $path);
        $jpegPath = "{$base}.jpg";
        $counter = 1;

        while ($disk->exists($jpegPath)) {
            $jpegPath = "{$base}-{$counter}.jpg";
            $counter++;
        }

        return $jpegPath;
    }

    private function load(string $contents, string $path): ?GdImage
    {
        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            return null;
        }

        return $this->applyExifOrientation($image, $this->exifOrientation($contents, $path));
    }

    private function resize(GdImage $image, int $maxWidth, ?int $maxHeight = null): GdImage
    {
        $scale = min(1, $maxWidth / imagesx($image), $maxHeight ? $maxHeight / imagesy($image) : 1);

        if ($scale >= 1) {
            return $image;
        }

        $resized = imagescale($image, (int) round(imagesx($image) * $scale), -1, IMG_BICUBIC);

        return $resized instanceof GdImage ? $resized : $image;
    }

    private function encode(GdImage $image, string $format): ?string
    {
        ob_start();

        match ($format) {
            'jpeg' => imagejpeg($this->flatten($image), null, self::QUALITY),
            'png' => (function () use ($image) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, null, 9);
            })(),
            'webp' => imagewebp($image, null, self::QUALITY),
        };

        return ob_get_clean() ?: null;
    }

    /**
     * JPEG nema providnost, pa sliku postavljamo na bijelu pozadinu.
     */
    private function flatten(GdImage $image): GdImage
    {
        $flattened = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($flattened, 0, 0, imagecolorallocate($flattened, 255, 255, 255));
        imagecopy($flattened, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imageinterlace($flattened, true);

        return $flattened;
    }

    /**
     * Provjerava da li slika ima (bar djelimično) providne piksele, na uzorku piksela.
     */
    private function hasTransparency(GdImage $image): bool
    {
        if (! imageistruecolor($image)) {
            return imagecolortransparent($image) !== -1;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $step = max(1, (int) floor(sqrt($width * $height / 250000)));

        for ($y = 0; $y < $height; $y += $step) {
            for ($x = 0; $x < $width; $x += $step) {
                if ((imagecolorat($image, $x, $y) >> 24) & 0x7F) {
                    return true;
                }
            }
        }

        return false;
    }

    private function exifOrientation(string $contents, string $path): int
    {
        if ($this->format($path) !== 'jpeg' || ! function_exists('exif_read_data')) {
            return 1;
        }

        $exif = @exif_read_data('data://image/jpeg;base64,' . base64_encode($contents));

        return (int) ($exif['Orientation'] ?? 1);
    }

    private function applyExifOrientation(GdImage $image, int $orientation): GdImage
    {
        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);

        return $rotated instanceof GdImage ? $rotated : $image;
    }
}
