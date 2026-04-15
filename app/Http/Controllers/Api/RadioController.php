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
        $currentTrackData = Cache::get(TrackOrder::CURRENT_TRACK);

        if (!$currentTrackData) {
            return response()->json([
                'title' => 'Standard',
                'artist' => 'Classic'
            ]);
        }

        $mediaId = is_array($currentTrackData) ? $currentTrackData['id'] : $currentTrackData->id;
        $currentTrack = Media::with('composer')->find($mediaId);

        if (!$currentTrack) {
            return response()->json([
                'title' => 'Standard',
                'artist' => 'Classic'
            ]);
        }

        return response()->json([
            'title' => $currentTrack->title,
            'artist' => $currentTrack->artist,
            'composer_name' => $currentTrack->composer?->name,
            'composer_image' => $currentTrack->composer?->imageUrl,
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

        // 2. Odredi sort_order i pivot_id zadnje pjesme
        $lastTrackData = Cache::get(TrackOrder::NEXT_TRACK);
        $lastPlayedSortOrder = -1;
        $lastPlayedPivotId = -1;

        if ($lastTrackData && is_array($lastTrackData)) {
            $pivotId = $lastTrackData['pivot_id'] ?? null;

            if ($pivotId) {
                $found = $playlist->media->first(fn($item) => $item->pivot->id == $pivotId);
                if ($found) {
                    $lastPlayedSortOrder = $found->pivot->sort_order;
                    $lastPlayedPivotId = $found->pivot->id;
                }
            }
        }

        // 3. Pronađi sledeću pesmu
        // Prvo tražimo pesmu sa većim sort_orderom
        // Ili sa istim sort_orderom ali većim pivot ID-jem (da pokrijemo slučaj kada su svi 0)
        $nextItem = $playlist->media
            ->filter(function ($item) use ($lastPlayedSortOrder, $lastPlayedPivotId) {
                $currentSortOrder = $item->pivot->sort_order;
                $currentPivotId = $item->pivot->id;

                if ($currentSortOrder > $lastPlayedSortOrder) {
                    return true;
                }

                if ($currentSortOrder == $lastPlayedSortOrder && $currentPivotId > $lastPlayedPivotId) {
                    return true;
                }

                return false;
            })
            ->sortBy([
                ['pivot.sort_order', 'asc'],
                ['pivot.id', 'asc'],
            ])
            ->first();

        // 4. Cirkularna logika - ako nema sledeće, uzmi prvu po sort_orderu i pivot ID-u
        if (!$nextItem) {
            $nextItem = $playlist->media
                ->sortBy([
                    ['pivot.sort_order', 'asc'],
                    ['pivot.id', 'asc'],
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
