<?php

namespace App\Filament\Resources\Composers\Pages;

use App\Filament\Resources\Composers\ComposerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComposer extends CreateRecord
{
    protected static string $resource = ComposerResource::class;

    protected static ?string $title = 'Novi kompozitor';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Sačuvaj'),
            $this->getCancelFormAction()
                ->label('Odustani'),
        ];
    }
}
