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
                Section::make('Osnovne Informacije')
                    ->description('Unesite naslov, autora i tip medija.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Naziv')
                                    ->required()
                                    ->placeholder('npr. Mesečeva Sonata'),

                                Select::make('media_type_id')
                                    ->label('Tip medija')
                                    ->relationship('type', 'name')
                                    ->required()
                                    ->native(false)
                                    ->preload(),

                                TextInput::make('artist')
                                    ->label('Izvođač / Kompozitor')
                                    ->placeholder('npr. Ludwig van Beethoven'),

                                TextInput::make('duration')
                                    ->label('Trajanje')
                                    ->numeric()
                                    ->suffix('s')
                                    ->helperText('Ovo će biti automatski izračunato'),
                            ]),
                    ]),

                Section::make('Multimedija')
                    ->description('Otpremite audio fajl i prateću sliku za plejer.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('Audio fajl')
                                    ->disk('radio')
                                    ->directory('audio')
                                    ->acceptedFileTypes(['audio/mpeg'])
                                    ->live()
                                    // Slugifikacija naziva audio fajla sa timestampom
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                        return (string) str($name)->slug()->append('-' . time() . '.' . $file->getClientOriginalExtension());
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (! $state instanceof TemporaryUploadedFile) {
                                            return;
                                        }

                                        $getID3 = new getID3();
                                        $getID3->option_tag_id3v2 = true;
                                        $getID3->option_tag_id3v1 = true;

                                        $path = $state->getRealPath();
                                        $fileInfo = $getID3->analyze($path);

                                        if (isset($fileInfo['playtime_seconds'])) {
                                            $set('duration', (int) round($fileInfo['playtime_seconds']));
                                        }

                                        // Izvlačenje metapodataka
                                        $title = $fileInfo['tags']['id3v2']['title'][0] ?? null;
                                        $artist = $fileInfo['tags']['id3v2']['artist'][0] ?? null;

                                        if ($title) $set('title', $title);
                                        if ($artist) $set('artist', $artist);

                                        // 2. Izvlačenje Cover Art-a
                                        $picture = null;
                                        if (!empty($fileInfo['comments']['picture'][0]['data'])) {
                                            $picture = $fileInfo['comments']['picture'][0];
                                        } elseif (!empty($fileInfo['id3v2']['APIC'][0]['data'])) {
                                            $picture = $fileInfo['id3v2']['APIC'][0];
                                        }

                                        if ($picture && isset($picture['data'])) {
                                            try {
                                                $mime = $picture['image_mime'] ?? $picture['mime'] ?? 'image/jpeg';
                                                $extension = str_replace('image/', '', $mime);
                                                $extension = $extension === 'jpeg' ? 'jpg' : $extension;

                                                // Generisanje naziva slike na osnovu naslova (iz tagova ili unesenog) + timestamp
                                                $baseName = $title ?? pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME);
                                                $imageName = (string) str($baseName)->slug()->append('-' . time() . '.' . $extension);

                                                Storage::disk('radio-covers')->put($imageName, $picture['data']);

                                                $set('image_path', $imageName);
                                            } catch (\Exception $e) {
                                                \Illuminate\Support\Facades\Log::error("Greška pri čuvanju cover arta: " . $e->getMessage());
                                            }
                                        }
                                    })
                                    ->required()
                                    ->columnSpan(1),

                                FileUpload::make('image_path')
                                    ->label('Slika')
                                    ->image()
                                    ->disk('radio-covers')
                                    ->imageEditor()
                                    // Slugifikacija naziva slike sa timestampom pri ručnom uploadu
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                        return (string) str($name)->slug()->append('-' . time() . '.' . $file->getClientOriginalExtension());
                                    })
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }
}
