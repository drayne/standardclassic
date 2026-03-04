<?php

namespace App\Filament\Widgets;

use App\Models\MediaSchedule;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingScheduleWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->poll('15s')
            ->query(
                MediaSchedule::query()
                    ->with(['media', 'media.type'])
                    ->where('played', false)
                    ->where('scheduled_at', '>', now())
                    ->orderBy('scheduled_at', 'asc')
                    ->limit(10)
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
                        Tables\Columns\TextColumn::make('scheduled_at')
                            ->formatStateUsing(fn ($state) => $state->format('H:i'))
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-clock')
                            ->description(fn($state) => $state->format('d.m.Y'), position: 'below')
                            ->alignEnd(),
                    ])->grow(false),
                ]),
            ])
            ->paginated(false)
            ->header(null)
            ->heading('Predstojeće zakazane emisije')
            ->emptyStateHeading('Nema zakazanih emisija');
    }
}
