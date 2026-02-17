<?php

namespace App\Http\Services;

use App\Enums\TrackOrder;
use App\Models\Media;
use Cache;

class CurrentlyPlayingService
{
    public static function updateTracks(Media $nextItem): void
    {
        $currentlyPlayingOrder = Cache::get(TrackOrder::NEXT_TRACK);

        if ($currentlyPlayingOrder !== null) {
            Cache::put(TrackOrder::CURRENT_TRACK, $currentlyPlayingOrder);
        } else {
            Cache::put(TrackOrder::CURRENT_TRACK, $nextItem->pivot->sort_order);
        }

        Cache::put(TrackOrder::NEXT_TRACK, $nextItem->pivot->sort_order);
    }
}
