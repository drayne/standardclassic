<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        $routeName = $request->route()->getName();

        $slug = match ($routeName) {
            'vijesti-sa-dankom' => 'vijesti-sa-dankom-iz-zemlje-i-svijeta',
            'popodne-sa-Acom-Informacijom' => 'popodne-sa-acom-informacijom',
            default => abort(404),
        };

        $show = Show::where('slug', $slug)->firstOrFail();

        $episodes = $show->episodes()
            ->with('media')
            ->orderBy('datum', 'desc')
            ->paginate(10);

        $episodes->getCollection()->transform(function ($episode) {
            $isYoutube = false;
            $videoUrl = null;
            $audioUrl = null;

            if ($episode->media) {
                $audioUrl = route('media.stream', $episode->media_id);
            } elseif ($episode->external_url) {
                if (str_contains($episode->external_url, 'youtube.com') || str_contains($episode->external_url, 'youtu.be')) {
                    $isYoutube = true;
                    // Konverzija u embed URL
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $episode->external_url, $match)) {
                        $videoUrl = "https://www.youtube-nocookie.com/embed/" . $match[1];
                    } else {
                        $videoUrl = $episode->external_url;
                    }
                } else {
                    $audioUrl = $episode->external_url;
                }
            }

            return [
                'id' => $episode->id,
                'title' => $episode->title,
                'date' => $episode->datum ? $episode->datum->format('d.m.Y') : '',
                'summary' => $episode->description,
                'audio_url' => $audioUrl,
                'video_url' => $videoUrl,
                'is_youtube' => $isYoutube,
                'playing' => false,
            ];
        });

        $component = match ($routeName) {
            'vijesti-sa-dankom' => 'VijestiSaDankom',
            'popodne-sa-Acom-Informacijom' => 'PopodneSaAcomInformacijom',
            default => abort(404),
        };

        return Inertia::render($component, [
            'episodes' => $episodes->toArray()
        ]);
    }
}
