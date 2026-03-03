<?php

namespace App\Filament\Resources\MediaTypes\Pages;

use App\Filament\Resources\MediaTypes\MediaTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaType extends CreateRecord
{
    protected static string $resource = MediaTypeResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }
}
