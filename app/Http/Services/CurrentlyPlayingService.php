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

        if ($wasNext instanceof Media) {
            Cache::put(TrackOrder::CURRENT_TRACK, $wasNext);
        }

        Cache::put(TrackOrder::NEXT_TRACK, $newTrack);
    }

    /**
     * Zakazana pjesma odmah postaje CURRENT - dok NEXT ne diramo
     *
     * @param Media $media
     * @return void
     */
    public static function setScheduledTrack(Media $media): void
    {
        Cache::put(TrackOrder::CURRENT_TRACK, $media);
    }
}
