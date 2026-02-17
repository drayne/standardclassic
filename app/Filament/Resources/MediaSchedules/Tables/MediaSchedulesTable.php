<?php

namespace App\Filament\Resources\MediaSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaSchedulesTable
{
    public static function configure(Table $table): Table
    {
//        return $table
//            ->columns([
//                //
//            ])
//            ->filters([
//                //
//            ])
//            ->recordActions([
//                EditAction::make(),
//            ])
//            ->toolbarActions([
//                BulkActionGroup::make([
//                    DeleteBulkAction::make(),
//                ]),
//            ]);

        return $table
            ->columns([
                // Prikazuje naslov medija preko relacije
                TextColumn::make('media.title')
                    ->label('Medijski fajl')
                    ->searchable()
                    ->sortable(),

                // Prikazuje datum i vreme
                TextColumn::make('scheduled_at')
                    ->label('Vreme emitovanja')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                // Indikator da li je već pušteno
                IconColumn::make('played')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                // Dodajemo i informaciju o izvođaču ako postoji u media tabeli
                TextColumn::make('media.artist')
                    ->label('Izvođač')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Ovde kasnije možemo dodati filter za "Samo buduća emitovanja"
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
