<?php

namespace App\Filament\Pages;

use App\Services\PlaylistForecastService;
use App\Services\PlaylistOrderService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PlaylistOrder extends Page
{
    protected static ?string $navigationLabel = 'Uređivanje programa';

    protected static string|\UnitEnum|null $navigationGroup = 'Radio';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsUpDown;

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Uređivanje programa';

    protected string $view = 'filament.pages.playlist-order';

    protected function getViewData(): array
    {
        $forecast = app(PlaylistForecastService::class)->forecast();

        return [
            'playlist_rows' => $forecast['playlist_rows'],
            ...$forecast['meta'],
        ];
    }

    public function movePlaylistItem(int|string $item, int|string $target, bool $before): void
    {
        app(PlaylistOrderService::class)->moveBefore((int) $item, (int) $target, $before);

        Notification::make()
            ->title('Redoslijed plejliste je sačuvan.')
            ->success()
            ->send();
    }
}
