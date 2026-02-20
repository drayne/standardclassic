<?php

namespace App\Filament\Widgets;

use App\Models\MediaSchedule;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingScheduleWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MediaSchedule::query()
                    ->with('media')
                    ->where('played', false)
                    ->where('scheduled_at', '>', now())
                    ->orderBy('scheduled_at', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('media.title')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->formatStateUsing(fn ($state) => $state->format('H:i') . ' (' . $state->diffForHumans() . ')'),
                BadgeColumn::make('played')
                    ->label('Status')
                    ->getStateUsing(fn () => 'Upcoming')
                    ->color('info'),
            ])
            ->paginated(false)
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
