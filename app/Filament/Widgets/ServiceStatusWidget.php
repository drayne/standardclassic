<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Http;

class ServiceStatusWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = 'everyMinute';

    protected int | string | array $columnSpan = [
        'lg' => 4,
    ];

    protected function getStats(): array
    {
        return [
            $this->getListenersCount(),
            $this->checkPort('Icecast Streaming', 'host.docker.internal', 8000, 'heroicon-m-signal'),
            $this->checkPort('Liquidsoap Engine', 'host.docker.internal', 1234, 'heroicon-m-bolt'),
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
        try {
            $response = Http::timeout(2)->get('http://host.docker.internal:8000/status-json.xsl');

            if ($response->successful()) {
                $data = $response->json();
                $sources = $data['icestats']['source'] ?? null;

                $currentListeners = 0;
                if ($sources) {
                    if (isset($sources['listeners'])) {
                        $currentListeners = $sources['listeners'];
                    } else if (is_array($sources)) {
                        foreach ($sources as $source) {
                            $currentListeners += $source['listeners'] ?? 0;
                        }
                    }
                }

                // Logika za PEAK (Najviše danas)
                // Cache se čuva do kraja dana (ponoć)
                $cacheKey = 'radio_peak_listeners_' . now()->format('Y-m-d');
                $peakListeners = cache()->get($cacheKey, 0);

                if ($currentListeners > $peakListeners) {
                    $peakListeners = $currentListeners;
                    cache()->put($cacheKey, $peakListeners, now()->endOfDay());
                }

                return Stat::make('Trenutno slušalaca', $currentListeners)
                    ->description("Najviše danas: {$peakListeners}")
                    ->descriptionIcon('heroicon-m-chart-bar-square')
                    ->color($currentListeners > 0 ? 'success' : 'gray')
                    ->icon('heroicon-m-user-group');
            }
        } catch (\Exception $e) {
            // Greška pri čitanju Icecast-a
        }

        return Stat::make('Trenutno slušalaca', '0')
            ->description('Icecast nedostupan')
            ->color('danger')
            ->icon('heroicon-m-user-group');
    }
}
