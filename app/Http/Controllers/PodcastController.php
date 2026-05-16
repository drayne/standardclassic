<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PodcastController extends Controller
{
    public function __invoke(Request $request)
    {
        $routeName = $request->route()->getName();

        $slug = match ($routeName) {
            'gdje-se-fura-nekultura' => 'gdje-se-fura-ne-kultura',
            'Milos-Stevanovic-standard-classic-podcast' => 'milos-stevanovic-standard-podkast',
            default => abort(404),
        };

        $podcast = Podcast::where('slug', $slug)->firstOrFail();

        $episodes = $podcast->episodes()
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
            'gdje-se-fura-nekultura' => 'GdjeSeFuraNekultura',
            'Milos-Stevanovic-standard-classic-podcast' => 'MilosStevanovicStandardClassicPodcast',
            default => abort(404),
        };

        return Inertia::render($component, [
            'episodes' => $episodes->toArray()
        ]);
    }
}
