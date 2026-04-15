<?php

namespace App\Filament\Widgets;

use App\Enums\TrackOrder;
use App\Models\Media;
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
        // Sada su u kešu asocijativni nizovi [id => ..., pivot_id => ...]
        $currentTrackData = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextTrackData = Cache::get(TrackOrder::NEXT_TRACK);

        $currentTrack = null;
        if ($currentTrackData) {
            $currentId = is_array($currentTrackData) ? $currentTrackData['id'] : $currentTrackData->id;
            $currentTrack = Media::with('composer')->find($currentId);
        }

        $nextTrack = null;
        if ($nextTrackData) {
            $nextId = is_array($nextTrackData) ? $nextTrackData['id'] : $nextTrackData->id;
            $nextTrack = Media::with('composer')->find($nextId);
        }

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
