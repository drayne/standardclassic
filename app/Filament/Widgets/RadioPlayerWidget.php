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
        'md' => 4,
        'xl' => 4,
    ];

    // Ovo će natjerati widget da se osvježi bez reload-a stranice
    protected static ?string $pollingInterval = '5s';

    // Koristićemo javna polja ili getData metodu
    protected function getViewData(): array
    {
        // Samo čitamo trenutne vrijednosti, ne mijenjamo ih!
        $currentTrack = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextTrack = Cache::get(TrackOrder::NEXT_TRACK);

//        $playlist = \App\Models\Playlist::where('active', true)->first();
        $protocol = app()->environment('local') ? 'http' : 'https';
        $host = config('radio.icecast_host');
        $port = config('radio.icecast_port');
        $icecastMount = config('radio.icecast_mount');

        return [
            'current' => $currentTrack,
            'next' => $nextTrack,
            'source' => $protocol . '://' . $host . ':' . $port . '/' . $icecastMount
        ];
    }

    public function skip(): void
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
