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
                    ->label('Slika')
                    ->disk('radio-covers')
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF&format=svg&icon=heroicon-s-musical-note')),
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
                    ->multiple()
                    ->mutateAttachFormDataUsing(function (array $data, RelationManager $livewire): array {
                        $maxOrder = $livewire->getRelationship()->max('sort_order') ?? 0;
                        $data['sort_order'] = $maxOrder + 1;
                        return $data;
                    }),
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
