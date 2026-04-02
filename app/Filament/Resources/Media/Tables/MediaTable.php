<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('type.name')
                    ->label('Tip')
                    ->formatStateUsing(fn (string $state): string => match (strtolower($state)) {
                        'song' => 'Pjesma',
                        'show' => 'Emisija',
                        'podcast' => 'Podkast',
                        default => $state,
                    })
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
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Naziv')
                    ->searchable(),
                TextColumn::make('composer.name')
                    ->label('Kompozitor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('artist')
                    ->label('Izvođač')
                    ->searchable(),
                TextColumn::make('duration')
                    ->label('Trajanje')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Detalji')
                    ->color('info'),
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Izmijeni')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->iconButton()
                    ->tooltip('Izbriši')
                    ->color('danger'),
            ])
            ->columnToggleFormColumns(0)
            ->bulkActions([
                DeleteBulkAction::make()->label('Izbriši izabrane'),
            ]);
    }
}
