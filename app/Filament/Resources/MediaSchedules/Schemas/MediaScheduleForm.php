<?php

namespace App\Filament\Resources\MediaSchedules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Zakazivanje emitovanja')
                    ->description('Izaberite medij i termin kada treba da se emituje mimo regularne plejliste.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('media_id')
                                    ->label('Naziv')
                                    ->relationship('media', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                DateTimePicker::make('scheduled_at')
                                    ->label('Vrijeme emitovanja')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d.m.Y H:i')
                                    ->seconds(false),
                    ])
                ])
        ]);
    }
}
