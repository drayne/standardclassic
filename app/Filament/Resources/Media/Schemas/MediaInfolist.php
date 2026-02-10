<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MediaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('media_type_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('artist')
                    ->placeholder('-'),
                TextEntry::make('file_path'),
                ImageEntry::make('image_path')
                    ->disk('radio-covers'),
                TextEntry::make('duration')
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
