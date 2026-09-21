<?php

namespace App\Filament\Pages;

use App\Services\PlaylistForecastService;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Program extends Page
{
    protected static ?string $navigationLabel = 'Predstojeći program';

    protected static string|\UnitEnum|null $navigationGroup = 'Radio';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Predstojeći program';

    protected string $view = 'filament.pages.program';

    protected function getViewData(): array
    {
        $forecast = app(PlaylistForecastService::class)->forecast();

        return [
            'rows' => $forecast['rows'],
            ...$forecast['meta'],
        ];
    }
}
