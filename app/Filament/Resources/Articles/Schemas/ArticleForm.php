<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Osnovne informacije')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategorija')
                            ->relationship('category', 'name')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Datum objave'),
                        FileUpload::make('image')
                            ->label('Slika')
                            ->image()
                            ->directory('articles')
                            ->columnSpanFull(),
                    ]),

                Section::make('Prevodi')
                    ->description('Dodajte naslov i sadržaj na različitim jezicima.')
                    ->schema([
                        Repeater::make('translations')
                            ->relationship()
                            ->schema([
                                Select::make('language_id')
                                    ->label('Jezik')
                                    ->relationship('language', 'name')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Naslov')
                                    ->required()
                                    ->live(onBlur: true),
                                RichEditor::make('content')
                                    ->label('Sadržaj')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(array $state): ?string => \App\Models\Language::find($state['language_id'])?->name ?? 'Novi prevod'),
                    ]),
            ]);
    }
}
