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
                Section::make('Multimedija')
                    ->description('Otpremite audio fajl.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('Audio fajl')
                                    ->disk('radio')
                                    ->directory('audio')
                                    ->acceptedFileTypes(['audio/mpeg', 'audio/mp4', 'audio/x-m4a'])
                                    ->maxSize(307200) // 300MB
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

                                        if ($title) {
                                            $set('title', $title);
                                        } else {
                                            $filename = pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME);
                                            // Zamjena donjih crta i povlaka razmacima radi ljepšeg prikaza
                                            $cleanName = str_replace(['_', '-'], ' ', $filename);
                                            $set('title', ucfirst($cleanName));
                                        }

                                        if ($artist) $set('artist', $artist);
                                    })
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ]),

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
                                    ->options([
                                        1 => 'Pjesma',
                                        2 => 'Emisija',
                                        3 => 'Podkast',
                                        4 => 'Džingl',
                                    ])
                                    ->default(1)
                                    ->required()
                                    ->native(false),

                                Select::make('composer_id')
                                    ->label('Kompozitor')
                                    ->relationship('composer', 'name')
                                    ->searchable()
                                    ->placeholder('Pretraži kompozitora...')
                                    ->preload()
                                    ->columnSpan(1),

                                TextInput::make('artist')
                                    ->label('Izvođač')
                                    ->placeholder('npr. Artur Rubinštajn'),

                                TextInput::make('duration')
                                    ->label('Trajanje')
                                    ->numeric()
                                    ->suffix('s')
                                    ->helperText('Ovo će biti automatski izračunato'),
                            ]),
                    ]),
            ]);
    }
}
