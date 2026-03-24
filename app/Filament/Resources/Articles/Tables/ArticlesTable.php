<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Naslov')
                    ->getStateUsing(fn ($record) => $record->translations->where('language.code', 'sr')->first()?->title ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategorija')
                    ->badge()
                    ->extraAttributes(['class' => 'text-xs'])
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('active')
                    ->label('Aktivna')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Slika')
                    ->disk('article-images'),
                TextColumn::make('published_at')
                    ->label('Objavljeno')
                    ->dateTime()
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
                    ->color('warning')
                    ->modalHeading('Izmijeni vijest')
                    ->modalSubmitActionLabel('Sačuvaj'),
            ])
            ->columnToggleFormColumns(0)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Izbriši izabrane'),
                ]),
            ]);
    }
}
