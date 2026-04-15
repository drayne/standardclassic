<?php

namespace App\Http\Services;

use App\Enums\TrackOrder;
use App\Models\Media;
use Cache;

class CurrentlyPlayingService
{
    public static function updateTracks(Media $newTrack): void
    {
        $wasNext = Cache::get(TrackOrder::NEXT_TRACK);

        if ($wasNext) {
            Cache::put(TrackOrder::CURRENT_TRACK, $wasNext);
        }

        // Čuvamo media_id i pivot_id (id u playlist_media tabeli)
        $trackData = [
            'id' => $newTrack->id,
            'pivot_id' => $newTrack->pivot?->id,
        ];

        Cache::put(TrackOrder::NEXT_TRACK, $trackData);
    }

    /**
     * Zakazana pjesma odmah postaje CURRENT - dok NEXT ne diramo
     *
     * @param Media $media
     * @return void
     */
    public static function setScheduledTrack(Media $media): void
    {
        $trackData = [
            'id' => $media->id,
            'pivot_id' => $media->pivot?->id,
        ];

        Cache::put(TrackOrder::CURRENT_TRACK, $trackData);
    }
}
