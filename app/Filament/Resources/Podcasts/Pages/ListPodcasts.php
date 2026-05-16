<?php

namespace App\Filament\Resources\Podcasts\Pages;

use App\Filament\Resources\Podcasts\PodcastResource;
use Filament\Resources\Pages\ListRecords;

class ListPodcasts extends ListRecords
{
    protected static string $resource = PodcastResource::class;

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
