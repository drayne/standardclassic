<?php

namespace App\Filament\Resources\MediaSchedules\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MediaScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Osnovne informacije')
                    ->columnSpan(1)
                    ->schema([
                        Select::make('media_id')
                            ->label('Naziv')
                            ->relationship('media', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Section::make('Vrijeme emitovanja')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('scheduled_times')
                            ->label('Termini')
                            ->schema([
                                DateTimePicker::make('scheduled_at')
                                    ->hiddenLabel()
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d.m.Y H:i')
                                    ->seconds(false),
                            ])
                            ->minItems(1)
                            ->createItemButtonLabel('Dodaj novi termin')
                            ->grid(5)
                            ->reorderable(false)
                            ->hidden(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),

                        DateTimePicker::make('scheduled_at')
                            ->label('Vrijeme emitovanja')
                            ->required()
                            ->native(false)
                            ->displayFormat('d.m.Y H:i')
                            ->seconds(false)
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
                    ]),
            ]);
    }
}
