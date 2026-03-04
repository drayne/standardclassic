<?php

namespace App\Filament\Resources\MediaSchedules\Pages;

use App\Filament\Resources\MediaSchedules\MediaScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaSchedule extends CreateRecord
{
    protected static string $resource = MediaScheduleResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }
}
