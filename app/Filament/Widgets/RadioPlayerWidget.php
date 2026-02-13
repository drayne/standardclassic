<?php

namespace App\Filament\Widgets;

use App\Enums\TrackOrder;
use App\Models\Playlist;
use App\Http\Services\RadioService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class RadioPlayerWidget extends Widget
{
    protected string $view = 'filament.widgets.radio-player-widget';
    protected int | string | array $columnSpan = 'full';

    // Ovo će natjerati widget da se osvježi bez reload-a stranice
    protected static ?string $pollingInterval = '5s';

    // Koristićemo javna polja ili getData metodu
    protected function getViewData(): array
    {
        $currentOrder = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextOrder = Cache::get(TrackOrder::NEXT_TRACK);
        $playlist = Playlist::where('active', true)->first();

        return [
            'current' => $playlist?->media()->wherePivot('sort_order', $currentOrder)->first(),
            'next' => $playlist?->media()->wherePivot('sort_order', $nextOrder)->first(),
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
