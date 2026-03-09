<?php

namespace App\Filament\Resources\MediaSchedules\Pages;

use App\Filament\Resources\MediaSchedules\MediaScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaSchedule extends CreateRecord
{
    protected static string $resource = MediaScheduleResource::class;

    public function getTitle(): string
    {
        return 'Nova zakazana emisija';
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
