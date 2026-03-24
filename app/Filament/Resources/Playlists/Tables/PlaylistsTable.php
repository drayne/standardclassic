<?php

namespace App\Filament\Resources\Playlists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlaylistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('active')
                    ->label('Aktivna')
                    ->boolean(),
                TextColumn::make('media_count')
                    ->label('Broj zapisa')
                    ->counts('media')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Poslednje ažuriranje')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Izmijeni')
                    ->color('warning'),
            ])
            ->columnToggleFormColumns(0)
            ->bulkActions([
                DeleteBulkAction::make()->label('Izbriši izabrane'),
            ]);
    }
}
