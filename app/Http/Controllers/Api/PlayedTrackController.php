<?php

namespace App\Http\Controllers\Api;

use App\Enums\TrackOrder;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\PlayedTrack;
use App\Models\TrackType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlayedTrackController extends Controller
{
    public function report(Request $request)
    {
        Log::info('Report pustene pjesme');
        $mediaId = $request->get('media_id');
        $typeName = $request->get('type');

        $type = TrackType::firstWhere('name', $typeName);

        $media = Media::find($mediaId);

        if ($media) {
            PlayedTrack::create([
                'media_id' => $mediaId,
                'track_type_id' => $type->id,
                'played_at' => now(),
            ]);

            // 3. Ažuriranje keša (za "Now Playing" widget)
            Cache::put(TrackOrder::CURRENT_TRACK, $media);

            \Log::info("Radio: Potvrđeno puštanje - {$media->title} [Tip: {$type->name}]");

            return response()->json(['status' => 'success']);
        }

        return response()->json(['error' => 'Media not found'], 404);
    }
}
