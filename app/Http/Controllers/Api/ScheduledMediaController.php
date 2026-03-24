<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\CurrentlyPlayingService;
use App\Models\MediaSchedule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Storage;

class ScheduledMediaController extends Controller
{
    public function check()
    {
        $now = Carbon::now('UTC')->addHour(); // na silu - jer pogresno cita zonu
        $startOfWindow = $now->copy()->subMinute();
        $endOfWindow = $now->copy()->addMinute();

        $schedule = MediaSchedule::whereBetween('scheduled_at', [$startOfWindow, $endOfWindow])
            ->where('played', false)
            ->with('media')
            ->first();

        \Log::info('Provjera sada: ' . $startOfWindow . ' - ' . $endOfWindow);
        \Log::info($schedule);

        if ($schedule && $schedule->media) {
            CurrentlyPlayingService::setScheduledTrack($schedule->media);
            $schedule->update(['played' => true]);

            $fullPath = Storage::disk('radio')->path($schedule->media->file_path);

            return response()->json([
                'status' => 'play_now',
                'title'  => $schedule->media->title,
                'artist' => $schedule->media->artist ?? 'Scheduled Event',
                'media_id' => $schedule->media->id,
                'path'   => $fullPath,
            ]);
        }

        return response()->json(['status' => 'idle', 'start' => $startOfWindow]);
    }
}
