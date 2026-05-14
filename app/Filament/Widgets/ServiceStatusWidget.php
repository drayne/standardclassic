<?php

namespace App\Filament\Widgets;

use App\Http\Services\RadioService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Http;

class ServiceStatusWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = [
        'lg' => 4,
    ];

    protected function getStats(): array
    {
        return [
            $this->getListenersCount(),
            $this->checkPort('Icecast Streaming', config('radio.icecast_host'), config('radio.icecast_port'), 'heroicon-m-signal'),
            $this->checkPort('Liquidsoap Engine', config('radio.icecast_host'), config('radio.icecast_telnet_port'), 'heroicon-m-bolt'),
        ];
    }

    private function checkPort($name, $host, $port, $icon): Stat
    {
        // Pokušaj konekcije sa timeoutom od 1 sekunde
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);

        if ($connection) {
            fclose($connection);
            return Stat::make($name, '')
                ->description('Servis radi na portu ' . $port)
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->icon($icon);
        }

        return Stat::make($name, '')
            ->description('Servis nije pokrenut!')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color('danger')
            ->icon($icon);
    }

    private function getListenersCount(): Stat
    {
        $radioService = app(RadioService::class);
        $stat = $radioService->getTodayStats();

        if (! $stat || ($stat->current === 0 && ! $this->isIcecastAvailable())) {
             return Stat::make('Trenutno slušalaca', '0')
                ->description('Icecast nedostupan ili nema podataka')
                ->color('danger')
                ->icon('heroicon-m-user-group');
        }

        return Stat::make('Trenutno slušalaca', $stat->current)
            ->description("Najviše danas: {$stat->highest}")
            ->descriptionIcon('heroicon-m-chart-bar-square')
            ->color($stat->current > 0 ? 'success' : 'gray')
            ->icon('heroicon-m-user-group');
    }

    private function isIcecastAvailable(): bool
    {
        $host = config('radio.icecast_host');
        $port = config('radio.icecast_port');
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
