<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMedia extends ViewRecord
{
    protected static string $resource = MediaResource::class;

    protected ?string $heading = 'Detalji medija';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
