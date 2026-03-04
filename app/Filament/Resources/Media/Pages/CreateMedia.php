<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    public function getTitle(): string
    {
        return 'Unos novog zapisa';
    }

    public function canCreateAnother(): bool
    {
        return false;
    }
}
