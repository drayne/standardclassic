<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\CurrentlyPlayingService;
use App\Models\MediaSchedule;
use Carbon\Carbon;

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

        if ($schedule) {
            CurrentlyPlayingService::updateTracks($schedule->media);
            $schedule->update(['played' => true]);

            $baseWslPath = '/home/vedran/radio/';
            $filePath = $baseWslPath . ltrim($schedule->media->file_path, '/');

            return response()->json([
                'status' => 'play_now',
                'file' => $filePath,
                'title' => $schedule->media->title
            ]);
        }

        return response()->json(['status' => 'idle', 'start' => $startOfWindow]);
    }
}
