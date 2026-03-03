<?php

namespace App\Filament\Resources\Playlists\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\BulkActionGroup;

class MediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Mediji na plejlisti';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Slika'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('artist')
                    ->label('Izvođač')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Dodaj na plejlistu')
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->actions([
                DetachAction::make()->label('Ukloni'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()->label('Ukloni'),
                ]),
            ])
            ->reorderable('sort_order'); // Enables drag-and-drop sorting
    }
}
