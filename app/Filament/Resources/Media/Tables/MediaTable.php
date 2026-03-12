<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                TextColumn::make('artist')
                    ->label('Izvođač')
                    ->searchable(),
                ImageColumn::make('image_path')
                    ->label('Slika')
                    ->disk('radio-covers')
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF&format=svg&icon=heroicon-s-musical-note')),
                TextColumn::make('duration')
                    ->label('Trajanje')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Detalji'),
                EditAction::make()->label('Izmijeni'),
            ])
            ->columnToggleFormColumns(0)
            ->bulkActions([
                DeleteBulkAction::make()->label('Izbriši izabrane'),
            ]);
    }
}
