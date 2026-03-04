<?php

namespace App\Filament\Resources\MediaSchedules\Pages;

use App\Filament\Resources\MediaSchedules\MediaScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMediaSchedules extends ListRecords
{
    protected static string $resource = MediaScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Dodaj novu zakazanu emisiju'),
        ];
    }
}
