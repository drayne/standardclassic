<?php

namespace App\Filament\Resources\MediaTypes\Pages;

use App\Filament\Resources\MediaTypes\MediaTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMediaType extends EditRecord
{
    protected static string $resource = MediaTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
