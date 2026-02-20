<?php

namespace App\Filament\Widgets;

use App\Models\PlayedTrack;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPlayedTracksWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PlayedTrack::query()
                    ->with('media')
                    ->latest('played_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('media.title')
                    ->label('Track'),
                Tables\Columns\TextColumn::make('media.artist')
                    ->label('Artist'),
                Tables\Columns\TextColumn::make('played_at')
                    ->since()
                    ->label('Played')
                    ->dateTime(),
            ])
            ->paginated(false)
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
