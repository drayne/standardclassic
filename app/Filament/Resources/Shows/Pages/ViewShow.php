<?php

namespace App\Filament\Resources\Shows\Pages;

use App\Filament\Resources\Shows\ShowResource;
use App\Models\Episode;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewShow extends ViewRecord
{
    protected static string $resource = ShowResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createEpisode')
                ->label('Dodaj epizodu')
                ->icon('heroicon-o-plus')
                ->modalHeading('Nova epizoda za '.$this->record->name)
                ->modalSubmitActionLabel('Dodaj')
                ->modalWidth('xl')
                ->form([
                    TextInput::make('title')
                        ->label('Naslov')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Opis')
                        ->rows(3),
                    DatePicker::make('datum')
                        ->label('Datum')
                        ->native(false)
                        ->displayFormat('d.m.Y')
                        ->default(now())
                        ->required(),
                    Select::make('media_id')
                        ->label('Medij (emisija iz naše baze)')
                        ->options(\App\Models\Media::whereHas('type', fn ($query) => $query->where('name', 'show'))->pluck('title', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->disabled(fn (callable $get) => filled($get('external_url')))
                        ->requiredWithout('external_url')
                        ->prohibitedIf('external_url', fn ($state, $get) => filled($get('external_url'))),
                    TextInput::make('external_url')
                        ->label('YouTube link')
                        ->url()
                        ->maxLength(255)
                        ->live()
                        ->disabled(fn (callable $get) => filled($get('media_id')))
                        ->requiredWithout('media_id')
                        ->prohibitedIf('media_id', fn ($state, $get) => filled($get('media_id'))),
                ])
                ->action(function (array $data, \Filament\Resources\Pages\ViewRecord $livewire): void {
                    $data['show_id'] = $this->record->id;

                    Episode::create($data);

                    $livewire->dispatch('refresh_episodes');

                    Notification::make()
                        ->title('Epizoda kreirana')
                        ->success()
                        ->send();
                }),
        ];
    }
}
