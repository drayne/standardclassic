<?php

namespace App\Filament\Resources\Podcasts\Pages;

use App\Filament\Resources\Podcasts\PodcastResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePodcasts extends ManageRecords
{
    protected static string $resource = PodcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
