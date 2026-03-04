<?php

namespace App\Filament\Widgets;

use App\Models\PlayedTrack;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPlayedTracksWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->query(
                PlayedTrack::query()
                    ->with(['media', 'media.type'])
                    ->latest('played_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('media.image_path')
                        ->circular()
                        ->defaultImageUrl(url('/images/default-music.png'))
                        ->disk('radio-covers')
                        ->grow(false),
                    Tables\Columns\IconColumn::make('media.type.name')
                        ->label('Tip')
                        ->icon(fn (string $state): string => match (strtolower($state)) {
                            'song' => 'heroicon-o-musical-note',
                            'show' => 'heroicon-o-microphone',
                            'podcast' => 'heroicon-o-megaphone',
                            default => 'heroicon-o-question-mark-circle',
                        })
                        ->color(fn (string $state): string => match (strtolower($state)) {
                            'song' => 'primary',
                            'show' => 'success',
                            'podcast' => 'warning',
                            default => 'gray',
                        })
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('media.title')
                            ->weight('bold')
                            ->color('slate-900')
                            ->size('sm'),
                        Tables\Columns\TextColumn::make('media.artist')
                            ->color('gray-500')
                            ->size('xs'),
                    ])->space(1),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('played_at')
                            ->since()
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-m-play')
                            ->alignEnd(),
                    ])->grow(false),
                ]),
            ])
            ->paginated(false)
            ->header(null)
            ->heading('Poslednje reprodukovano');
    }
}
