<?php

namespace App\Filament\Resources\MediaTypes\Pages;

use App\Filament\Resources\MediaTypes\MediaTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMediaType extends ViewRecord
{
    protected static string $resource = MediaTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
