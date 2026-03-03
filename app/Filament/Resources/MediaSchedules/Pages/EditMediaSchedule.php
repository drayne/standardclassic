<?php

namespace App\Filament\Resources\MediaSchedules\Pages;

use App\Filament\Resources\MediaSchedules\MediaScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMediaSchedule extends EditRecord
{
    protected static string $resource = MediaScheduleResource::class;

    protected ?string $heading = 'Izmijeni zakazanu emisiju';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
