<?php

namespace App\Filament\Resources\Shows\Pages;

use App\Filament\Resources\Shows\ShowResource;
use Filament\Resources\Pages\ListRecords;

class ListShows extends ListRecords
{
    protected static string $resource = ShowResource::class;

    public function getContentTabLabel(): ?string
    {
        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
