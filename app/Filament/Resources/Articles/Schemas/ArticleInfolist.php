<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('category.name')
                            ->hiddenLabel()
                            ->badge()
                            ->extraAttributes(['class' => 'text-xs'])
                            ->color('primary'),

                        TextEntry::make('title')
                            ->hiddenLabel()
                            ->getStateUsing(fn ($record) => $record->translations->where('language.code', 'sr')->first()?->title ?? '-')
                            ->size('2xl')
                            ->extraAttributes(['class' => 'text-black dark:text-white font-bold text-2xl'])
                            ->columnSpanFull(),

                        TextEntry::make('published_at')
                            ->hiddenLabel()
                            ->dateTime('d.m.Y H:i')
                            ->size('sm')
                            ->color('gray')
                            ->columnSpanFull(),

                        ImageEntry::make('image')
                            ->hiddenLabel()
                            ->disk('article-images')
                            ->extraImgAttributes([
                                'style' => 'max-width: 100%; max-height: 400px; width: auto; height: auto; object-fit: contain; border-radius: 0.5rem;',
                            ])
                            ->columnSpanFull()
                            ->placeholder('-'),

                        ImageEntry::make('gallery')
                            ->label('Galerija')
                            ->disk('article-images')
                            ->imageHeight(120)
                            ->visible(fn ($record) => filled($record->gallery))
                            ->columnSpanFull(),

                        TextEntry::make('content')
                            ->hiddenLabel()
                            ->html()
                            ->getStateUsing(fn ($record) => $record->translations->where('language.code', 'sr')->first()?->content ?? '-')
                            ->columnSpanFull(),
                    ])
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm'])
                    ->columnSpanFull()
            ]);
    }
}
