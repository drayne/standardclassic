<?php

namespace App\Filament\Widgets;

use App\Enums\TrackOrder;
use App\Models\Playlist;
use App\Http\Services\RadioService;
use Filament\Widgets\Widget;
use Http;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class RadioPlayerWidget extends Widget
{
    protected string $view = 'filament.widgets.radio-player-widget';
    protected int | string | array $columnSpan = [
        'md' => 3,
        'xl' => 3,
    ];

    // Ovo će natjerati widget da se osvježi bez reload-a stranice
    protected static ?string $pollingInterval = '5s';

    // Koristićemo javna polja ili getData metodu
    protected function getViewData(): array
    {
        // Samo čitamo trenutne vrijednosti, ne mijenjamo ih!
        $currentOrder = Cache::get(\App\Enums\TrackOrder::CURRENT_TRACK);
        $nextOrder = Cache::get(\App\Enums\TrackOrder::NEXT_TRACK);

        $playlist = \App\Models\Playlist::where('active', true)->first();

        // Ako nema ništa u kešu (npr. tek upaljen server),
        // možemo uzeti prvu pjesmu ali BEZ upisivanja u keš ovdje.
        $current = $playlist?->media()->wherePivot('sort_order', $currentOrder)->first();
        $next = $playlist?->media()->wherePivot('sort_order', $nextOrder)->first();

        return [
            'current' => $current,
            'next' => $next,
        ];
    }

    public function skip()
    {
        $service = new RadioService();
        if ($service->skipTrack()) {
            Notification::make()->title('Pjesma preskočena!')->success()->send();
            // Prisilno osvježavanje podataka nakon skoka
            $this->dispatch('$refresh');
        } else {
            Notification::make()->title('Greška pri konekciji!')->danger()->send();
        }
    }
}
