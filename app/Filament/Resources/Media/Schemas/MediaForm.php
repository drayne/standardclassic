<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sekcija za osnovne tekstualne podatke
                Section::make('Osnovne Informacije')
                    ->description('Unesite naslov, autora i tip medija.')
                    ->schema([
                        Grid::make(2) // Dijelimo u dvije kolone
                        ->schema([
                            TextInput::make('title')
                                ->label('Naslov')
                                ->required()
                                ->placeholder('npr. Mesečeva Sonata'),

                            Select::make('media_type_id')
                                ->label('Tip medija')
                                ->relationship('type', 'name')
                                ->required()
                                ->native(false) // Ljepši UI za selekt
                                ->preload(),

                            TextInput::make('artist')
                                ->label('Izvođač / Autor')
                                ->placeholder('npr. Ludwig van Beethoven'),

                            TextInput::make('duration')
                                ->label('Trajanje (sekunde)')
                                ->numeric()
                                ->suffix('s')
                                ->helperText('Ovo će biti automatski izračunato (opciono)'),
                        ]),
                    ]),

                // Sekcija za fajlove (Audio i Ikonica)
                Section::make('Multimedija')
                    ->description('Otpremite audio fajl i prateću sliku za plejer.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Audio Upload sa tvojom slug logikom
                                FileUpload::make('file_path')
                                    ->label('Audio fajl (.mp3)')
                                    ->disk('radio')
                                    ->directory('audio')
                                    ->acceptedFileTypes(['audio/mpeg'])
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                            ->slug()
                                            ->append('.' . $file->getClientOriginalExtension()),
                                    )
                                    ->required()
                                    ->columnSpan(1),

                                // Image Upload sa editorom i slug logikom
                                FileUpload::make('image_path')
                                    ->label('Ikonica (Cover Art)')
                                    ->image()
                                    ->imageEditor() // Omogućava kropovanje
                                    ->circleCropper() // Odlično za plejer ikonice
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                            ->slug()
                                            ->append('.' . $file->getClientOriginalExtension()),
                                    )
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }
}
