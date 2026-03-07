<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Kategorija'),
                TextEntry::make('slug'),
                TextEntry::make('title')
                    ->label('Naslov')
                    ->getStateUsing(fn ($record) => $record->translations->where('language.code', 'sr')->first()?->title ?? '-'),
                TextEntry::make('content')
                    ->label('Sadržaj')
                    ->html()
                    ->getStateUsing(fn ($record) => $record->translations->where('language.code', 'sr')->first()?->content ?? '-')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
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
