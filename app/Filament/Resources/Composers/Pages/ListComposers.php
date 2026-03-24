<?php

namespace App\Filament\Resources\Composers\Pages;

use App\Filament\Resources\Composers\ComposerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComposers extends ListRecords
{
    protected static string $resource = ComposerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Dodaj kompozitora'),
        ];
    }
}
