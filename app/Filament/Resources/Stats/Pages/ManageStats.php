<?php

namespace App\Filament\Resources\Stats\Pages;

use App\Filament\Resources\Stats\StatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStats extends ManageRecords
{
    protected static string $resource = StatResource::class;

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Statistika slušalaca';
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return StatResource::getWidgets();
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }
}
