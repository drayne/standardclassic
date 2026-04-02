<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MediaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconEntry::make('type.name')
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
                    }),
                TextEntry::make('title')
                    ->label('Naziv'),
                TextEntry::make('artist')
                    ->label('Izvođač')
                    ->placeholder('-'),
                TextEntry::make('file_path')
                    ->label('Putanja fajla'),
                ImageEntry::make('composer.image')
                    ->label('Slika kompozitora')
                    ->disk('composer-images')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF&format=svg&icon=heroicon-s-musical-note')),
                TextEntry::make('duration')
                    ->label('Trajanje')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
