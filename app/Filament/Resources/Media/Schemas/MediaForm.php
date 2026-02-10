<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use getID3;

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
                                    ->label('Audio fajl')
                                    ->disk('radio')
                                    ->directory('audio')
                                    ->acceptedFileTypes(['audio/mpeg'])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) { // Uklonjen TemporaryUploadedFile tipizacija
                                        // Provjeravamo da li je state zapravo novi upload
                                        if (! $state instanceof TemporaryUploadedFile) {
                                            return;
                                        }

                                        $getID3 = new getID3();
                                        $getID3->option_tag_id3v2 = true; // Forsiraj čitanje v2 tagova
                                        $getID3->option_tag_id3v1 = true;

                                        $path = $state->getRealPath();
                                        $fileInfo = $getID3->analyze($path);

                                        // 1. Standardni podaci
                                        if (isset($fileInfo['playtime_seconds'])) {
                                            $set('duration', (int) round($fileInfo['playtime_seconds']));
                                        }

                                        if (isset($fileInfo['tags']['id3v2']['title'][0])) {
                                            $set('title', $fileInfo['tags']['id3v2']['title'][0]);
                                        }

                                        if (isset($fileInfo['tags']['id3v2']['artist'][0])) {
                                            $set('artist', $fileInfo['tags']['id3v2']['artist'][0]);
                                        }

                                        // 2. Izvlačenje Cover Art-a - "Deep Search" metoda
                                        $picture = null;

                                        // getID3 često smješta slike duboko u niz, pokušajmo ih "uloviti" redom
                                        if (!empty($fileInfo['comments']['picture'][0]['data'])) {
                                            $picture = $fileInfo['comments']['picture'][0];
                                        } elseif (!empty($fileInfo['id3v2']['APIC'][0]['data'])) {
                                            $picture = $fileInfo['id3v2']['APIC'][0];
                                        } elseif (!empty($fileInfo['id3v2']['comments']['picture'][0]['data'])) {
                                            $picture = $fileInfo['id3v2']['comments']['picture'][0];
                                        }

                                        if ($picture && isset($picture['data'])) {
                                            try {
                                                // Detekcija ekstenzije
                                                $mime = $picture['image_mime'] ?? $picture['mime'] ?? 'image/jpeg';
                                                $extension = str_replace('image/', '', $mime);
                                                $extension = $extension === 'jpeg' ? 'jpg' : $extension;

                                                $imageName = \Illuminate\Support\Str::random(40) . '.' . $extension;

                                                // Spasimo sliku na disk
                                                \Illuminate\Support\Facades\Storage::disk('radio-covers')->put($imageName, $picture['data']);

                                                // Setujemo polje na formi
                                                $set('image_path', $imageName);
                                            } catch (\Exception $e) {
                                                // Ako nešto pukne pri pisanju fajla, samo ignoriši i pusti korisnika da sam digne sliku
                                                \Illuminate\Support\Facades\Log::error("Greška pri čuvanju cover arta: " . $e->getMessage());
                                            }
                                        }
                                    })
                                    ->required()
                                    ->columnSpan(1),

                                // Image Upload sa editorom i slug logikom
                                FileUpload::make('image_path')
                                    ->label('Ikonica (Cover Art)')
                                    ->image()
                                    ->disk('radio-covers')
                                    ->imageEditor() // Omogućava kropovanje
//                                    ->circleCropper() // Odlično za plejer ikonice
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
