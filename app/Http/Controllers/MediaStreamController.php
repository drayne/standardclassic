<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaStreamController extends Controller
{
    public function stream(Media $media): StreamedResponse
    {
        if (!$media->file_path || !Storage::disk('radio')->exists($media->file_path)) {
            abort(404);
        }

        $path = Storage::disk('radio')->path($media->file_path);
        $size = Storage::disk('radio')->size($media->file_path);
        $mime = Storage::disk('radio')->mimeType($media->file_path);

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
