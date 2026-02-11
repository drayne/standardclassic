<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Cache;
use Illuminate\Http\Request;

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
        $lastOrder = Cache::get('radio_last_order', -1);

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
            // Zapamti ovaj sort_order za sljedeći poziv
            Cache::put('radio_last_order', $nextItem->pivot->sort_order);

            // PUTANJA: Prilagođavamo je tvojoj strukturi
            // file_path u bazi je vjerovatno 'audio/naslov-timestamp.mp3'
            // Zato pazimo da ne dupliramo /audio/
            $cleanPath = ltrim($nextItem->file_path, '/');

            // Finalna apsolutna putanja za WSL
            $fullPath = "/home/vedran/radio/" . $cleanPath;

            return response($fullPath, 200)
                ->header('Content-Type', 'text/plain');
        }

        return "/home/vedran/radio/audio/fallback.mp3";
    }
}
