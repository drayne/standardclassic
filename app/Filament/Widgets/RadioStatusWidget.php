<?php

namespace App\Filament\Widgets;

use App\Enums\TrackOrder;
use App\Models\Playlist;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class RadioStatusWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Uzimamo ključeve iz Enuma
        $currentOrder = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextOrder = Cache::get(TrackOrder::NEXT_TRACK);

        $playlist = Playlist::where('active', true)->first();

        if (!$playlist) {
            return [Stat::make('Radio', 'Offline')->color('danger')];
        }

        // Ako su oba reda ista, znači da je tek počeo prvi bafer
        // U tom slučaju "Next" treba da potraži pjesmu sa sort_orderom većim od trenutnog
        if ($currentOrder === $nextOrder) {
            $currentTrack = $playlist->media()->wherePivot('sort_order', $currentOrder)->first();
            // Ručno nađi sljedeću za prikaz u widgetu dok Liquidsoap ne pošalje drugi zahtjev
            $nextTrack = $playlist->media()
                ->wherePivot('sort_order', '>', $currentOrder)
                ->orderBy('sort_order', 'asc')
                ->first() ?? $playlist->media()->orderBy('sort_order', 'asc')->first();
        } else {
            $currentTrack = $playlist->media()->wherePivot('sort_order', $currentOrder)->first();
            $nextTrack = $playlist->media()->wherePivot('sort_order', $nextOrder)->first();
        }

        return [
            Stat::make('Trenutno u etru', $currentTrack?->title ?? 'Učitavanje...')
                ->description($currentTrack?->artist ?? 'StandardClassic')
                ->color('success')
                ->icon('heroicon-m-play-circle'),

            Stat::make('Sledeća (u baferu)', $nextTrack?->title ?? 'Kraj liste')
                ->description($nextTrack?->artist ?? 'Spremna za prelaz')
                ->color('info')
                ->icon('heroicon-m-forward'),
        ];
    }
}
