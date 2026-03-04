<?php

namespace App\Filament\Resources\MediaTypes\Pages;

use App\Filament\Resources\MediaTypes\MediaTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMediaTypes extends ListRecords
{
    protected static string $resource = MediaTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
