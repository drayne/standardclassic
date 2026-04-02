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

        // 2. Odredi sort_order i ID zadnje pjesme
        $lastTrackInQueue = Cache::get(TrackOrder::NEXT_TRACK);
        $lastPlayedSortOrder = -1;
        $lastPlayedId = -1;

        if ($lastTrackInQueue) {
            $found = $playlist->media->firstWhere('id', $lastTrackInQueue->id);
            if ($found && isset($found->pivot->sort_order)) {
                $lastPlayedSortOrder = $found->pivot->sort_order;
                $lastPlayedId = $found->id;
            }
        }

        // 3. Pronađi sledeću pesmu
        // Prvo tražimo pesmu sa većim sort_orderom
        // Ili sa istim sort_orderom ali većim ID-jem (da pokrijemo slučaj kada su svi 0)
        $nextItem = $playlist->media
            ->filter(function ($item) use ($lastPlayedSortOrder, $lastPlayedId) {
                $currentSortOrder = $item->pivot->sort_order;
                $currentId = $item->id;

                if ($currentSortOrder > $lastPlayedSortOrder) {
                    return true;
                }

                if ($currentSortOrder == $lastPlayedSortOrder && $currentId > $lastPlayedId) {
                    return true;
                }

                return false;
            })
            ->sortBy([
                ['pivot.sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->first();

        // 4. Cirkularna logika - ako nema sledeće, uzmi prvu po sort_orderu i ID-u
        if (!$nextItem) {
            $nextItem = $playlist->media
                ->sortBy([
                    ['pivot.sort_order', 'asc'],
                    ['id', 'asc'],
                ])
                ->first();
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

        return response()->json([
            'title'  => 'Fallback',
            'artist' => 'Radio',
            'path'   => $fallbackPath
        ]);
    }
}
