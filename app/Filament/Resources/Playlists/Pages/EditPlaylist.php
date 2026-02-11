<?php

namespace App\Filament\Resources\Playlists\Pages;

use App\Filament\Resources\Playlists\PlaylistResource;
use App\Http\Services\RadioService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPlaylist extends EditRecord
{
    protected static string $resource = PlaylistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            Action::make('skip')
                ->label('Preskoči trenutnu pjesmu')
                ->color('warning')
                ->icon('heroicon-o-forward')
                ->requiresConfirmation()
//                ->action(function () {
//                    // Pozivamo našu API metodu za skip
//                    $response = Http::get(url('/api/radio/skip'));
//
//                    if ($response->successful()) {
//                        \Filament\Notifications\Notification::make()
//                            ->title('Pjesma preskočena')
//                            ->success()
//                            ->send();
//                    }
//                }),
                ->action(function (RadioService $radioService) {
                    $result = $radioService->skipTrack();

                    if ($result) {
                        Notification::make()
                            ->title('Uspješno preskočeno')
                            ->body('Liquidsoap je primio komandu za sljedeću pjesmu.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Greška pri komunikaciji')
                            ->body('Nije moguće kontaktirati radio server. Provjerite da li je Liquidsoap pokrenut.')
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
