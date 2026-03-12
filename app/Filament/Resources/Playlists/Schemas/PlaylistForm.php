<?php

namespace App\Filament\Resources\Playlists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlaylistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Naziv')
                    ->required(),
                Toggle::make('active')
                    ->label('Aktivna')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
