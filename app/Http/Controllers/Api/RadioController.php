<?php

namespace App\Http\Controllers\Api;

use App\Enums\TrackOrder;
use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Cache;

class RadioController extends Controller
{
    public function getNextTrack()
    {
        // 1. Nađi aktivnu plejlistu
        $playlist = Playlist::where('active', true)->first();

        if (!$playlist) {
            // Ako nema aktivne plejliste, pusti neki fallback fajl
            return "/home/vedran/radio/audio/fallback.mp3";
        }

        // 2. Nađi posljednji pušteni redoslijed iz keša
        $lastOrder = Cache::get(TrackOrder::NEXT_TRACK, -1);

        // 3. Uzmi sljedeću pjesmu iz te plejliste (pazeći na sort_order)
        $nextItem = $playlist->media()
            ->wherePivot('sort_order', '>', $lastOrder)
            ->orderBy('sort_order', 'asc')
            ->first();

        // 4. Ako nema više pjesama (došli smo do kraja), vrati se na prvu
        if (!$nextItem) {
            $nextItem = $playlist->media()
                ->orderBy('sort_order', 'asc')
                ->first();
        }

        if ($nextItem) {
            // 1. Ono što je do sad bilo "Trenutno", sada postaje "Prethodno/Svirajuće"
            $currentlyPlayingOrder = Cache::get(TrackOrder::NEXT_TRACK);
            if ($currentlyPlayingOrder !== null) {
                Cache::put(TrackOrder::CURRENT_TRACK, $currentlyPlayingOrder);
            } else {
                // Ako je ovo APSOLUTNO prvi poziv, postavi trenutni na isti kao next
                // dok ne dođe drugi poziv (koji stiže milisekundu kasnije)
                Cache::put(TrackOrder::CURRENT_TRACK, $nextItem->pivot->sort_order);
            }

            // 2. Ažuriraj novi last_order (ono što Liquidsoap upravo baferuje)
            Cache::put(TrackOrder::NEXT_TRACK, $nextItem->pivot->sort_order);

            return response()->json([
                'title' => $nextItem->title ?? 'Unknown Title',
                'artist' => $nextItem->artist ?? 'StandardClassic',
                'path' => "/home/vedran/radio/" . ltrim($nextItem->file_path, '/'),
            ]);
        }

        return "/home/vedran/radio/audio/fallback.mp3";
    }
}
