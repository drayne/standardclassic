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
    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 1;

    protected function getTableContentHeight(): ?string
    {
        return '450px';
    }

    protected static ?string $pollingInterval = '5s';

    // Koristićemo javna polja ili getData metodu
    protected function getViewData(): array
    {
        // Samo čitamo trenutne vrijednosti, ne mijenjamo ih!
        $currentTrack = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextTrack = Cache::get(TrackOrder::NEXT_TRACK);

        if ($currentTrack instanceof \App\Models\Media) {
            $currentTrack->load('composer');
        }

        if ($nextTrack instanceof \App\Models\Media) {
            $nextTrack->load('composer');
        }

//        $playlist = \App\Models\Playlist::where('active', true)->first();
        $source = config('radio.icecast_stream_url');
        return [
            'current' => $currentTrack,
            'next' => $nextTrack,
            'source' => $source
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
