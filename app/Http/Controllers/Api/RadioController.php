<?php

namespace App\Http\Controllers\Api;

use App\Enums\TrackOrder;
use App\Http\Controllers\Controller;
use App\Http\Services\CurrentlyPlayingService;
use App\Models\Playlist;
use Cache;
use App\Models\Media;
use Illuminate\Support\Str;
use Storage;

class RadioController extends Controller
{
    public function getCurrentTrack()
    {
        $currentTrack = Cache::get(TrackOrder::CURRENT_TRACK);

        if (!$currentTrack instanceof Media) {
            return response()->json([
                'title' => 'Standard',
                'artist' => 'Classic'
            ]);
        }

        return response()->json([
            'title' => $currentTrack->title,
            'artist' => $currentTrack->artist,
        ]);
    }

    public function getNextTrack()
    {
        // 1. Aktivna plejlista sa učitanim medijima (Eager Loading)
        $playlist = Playlist::with('media')->where('active', true)->first();

        if (!$playlist || $playlist->media->isEmpty()) {
            \Log::error("Radio: Nema aktivne plejliste ili je plejlista prazna!");
            return $this->fallbackResponse();
        }

        // 2. Odredi sort_order
        $lastTrackInQueue = Cache::get(TrackOrder::NEXT_TRACK);
        $lastPlayedSortOrder = -1; // Počinjemo od -1 da bi prva pesma sa 0 bila validna

        if ($lastTrackInQueue) {
            // Koristimo kolekciju iz memorije umesto novog upita za brzinu i sigurnost
            $found = $playlist->media->firstWhere('id', $lastTrackInQueue->id);
            if ($found && isset($found->pivot->sort_order)) {
                $lastPlayedSortOrder = $found->pivot->sort_order;
            }
        }

        // 3. Pronađi sledeću pesmu
        $nextItem = $playlist->media
            ->where('pivot.sort_order', '>', $lastPlayedSortOrder)
            ->sortBy('pivot.sort_order')
            ->first();

        // 4. Cirkularna logika - ako nema sledeće, uzmi prvu
        if (!$nextItem) {
            $nextItem = $playlist->media->sortBy('pivot.sort_order')->first();
        }

        // 5. Finalna provera pre bilo kakvog pristupa propertijima
        if (!$nextItem) {
            \Log::error("Radio: Kritična greška - nextItem je null nakon svih provera.");
            return $this->fallbackResponse();
        }

        // Ažuriraj keš i vrati odgovor
        CurrentlyPlayingService::updateTracks($nextItem);

        \Log::info("Radio: Sledeća pesma spremna: " . $nextItem->title);

        $fullPath = Storage::disk('radio')->path($nextItem->file_path);
        if ($projectRoot = config('radio.radio_project_root')) {
            $fullPath = str_replace(base_path(), $projectRoot, $fullPath);
        }

        return response()->json([
            'title'  => $nextItem->title ?? 'Unknown Title',
            'artist' => $nextItem->artist ?? 'StandardClassic',
            'media_id' => $nextItem->id,
            'path'   => $fullPath
        ]);
    }

    private function fallbackResponse()
    {
        $fallbackPath = Storage::disk('radio')->path('fallback.mp3');
        if ($projectRoot = config('radio.radio_project_root')) {
            $fallbackPath = str_replace(base_path(), $projectRoot, $fallbackPath);
        }

        return response()->json([
            'title'  => 'Fallback',
            'artist' => 'Radio',
            'path'   => $fallbackPath
        ]);
    }
}
