<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getColumns(): int|array
    {
        return 4;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ServiceStatusWidget::class,
            \App\Filament\Widgets\RadioPlayerWidget::class,
            \App\Filament\Widgets\UpcomingScheduleWidget::class,
            \App\Filament\Widgets\LatestPlayedTracksWidget::class,
            \App\Filament\Widgets\ActivityLogWidget::class,
        ];
    }

    protected ?string $heading = '';
}
