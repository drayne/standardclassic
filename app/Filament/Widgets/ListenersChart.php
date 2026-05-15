<?php

namespace App\Filament\Widgets;

use App\Models\Stat;
use Filament\Widgets\ChartWidget;

class ListenersChart extends ChartWidget
{
    protected ?string $heading = 'Pregled slušalaca po danima (poslednjih 15 dana)';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Stat::orderBy('date', 'desc')
            ->limit(15)
            ->get()
            ->reverse()
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Najviše slušalaca (pik)',
                    'data' => $data->map(fn (Stat $stat) => $stat->highest)->toArray(),
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->map(fn (Stat $stat) => $stat->date->format('d.m.'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
