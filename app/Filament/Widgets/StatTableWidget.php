<?php

namespace App\Filament\Widgets;

use App\Models\Stat;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class StatTableWidget extends TableWidget
{
    protected static ?string $heading = 'Istorija slušalaca';

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Stat::query()->latest('date'))
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d.m.Y.')
                    ->sortable(),
                TextColumn::make('highest')
                    ->label('Najviše (pik)')
                    ->sortable(),
            ])
            ->paginated([5, 10, 25, 50, 'all']);
    }
}
