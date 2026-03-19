<?php

namespace App\Filament\Resources\CoverImages\Pages;

use App\Filament\Resources\CoverImages\CoverImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCoverImages extends ManageRecords
{
    protected static string $resource = CoverImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Dodaj naslovnu sliku')
                ->modalHeading('Dodaj naslovnu sliku')
                ->createAnother(false)
                ->modalSubmitActionLabel('Sačuvaj'),
        ];
    }
}
