<?php

namespace App\Filament\Resources\Composers\Pages;

use App\Filament\Resources\Composers\ComposerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComposer extends EditRecord
{
    protected static string $resource = ComposerResource::class;

    protected static ?string $title = 'Izmijeni kompozitora';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Sačuvaj'),
            $this->getCancelFormAction()
                ->label('Odustani'),
        ];
    }
}
