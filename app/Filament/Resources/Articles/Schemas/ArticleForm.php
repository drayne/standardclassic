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
            ->columns(1)
            ->components([
                Section::make('Osnovne informacije')
                    ->columns(1)
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategorija')
                            ->relationship('category', 'name')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Datum objave')
                            ->native(false)
                            ->displayFormat('d.m.Y H:i')
                            ->seconds(false),
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
                            ->hiddenLabel()
                            ->addActionLabel('Dodaj prevod')
                            ->relationship()
                            ->maxItems(fn() => \App\Models\Language::count())
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Select::make('language_id')
                                            ->label('Jezik')
                                            ->relationship('language', 'name')
                                            ->prefixIcon('heroicon-m-language')
                                            ->getOptionLabelFromRecordUsing(fn ($record) => match($record->code) {
                                                'sr' => '🇷🇸 ' . $record->name,
                                                'de' => '🇩🇪 ' . $record->name,
                                                'en' => '🇬🇧 ' . $record->name,
                                                default => $record->name,
                                            })
                                            ->required()
                                            ->distinct()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        TextInput::make('title')
                                            ->label('Naslov')
                                            ->required()
                                            ->live(onBlur: true),
                                        RichEditor::make('content')
                                            ->label('Sadržaj')
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->extraAttributes(fn(array $state): array =>
                                        match(\App\Models\Language::find($state['language_id'] ?? null)?->code) {
                                            'sr' => ['style' => 'background-color: #f0f7ff; border-left: 4px solid #3b82f6; padding: 1rem; border-radius: 0.5rem;'],
                                            'de' => ['style' => 'background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 0.5rem;'],
                                            'en' => ['style' => 'background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 0.5rem;'],
                                            default => [],
                                        }
                                    ),
                            ])
                            ->columns(1)
                            ->itemLabel(function (array $state): ?string {
                                if (empty($state['language_id'])) {
                                    return 'Novi prevod';
                                }
                                $language = \App\Models\Language::find($state['language_id']);
                                if (!$language) {
                                    return 'Novi prevod';
                                }
                                return match($language?->code) {
                                    'sr' => '🇷🇸 ' . $language->name,
                                    'de' => '🇩🇪 ' . $language->name,
                                    'en' => '🇬🇧 ' . $language->name,
                                    default => $language?->name ?? 'Novi prevod',
                                };
                            }),
                    ]),
            ]);
    }
}
