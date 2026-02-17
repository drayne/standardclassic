<?php

namespace App\Filament\Resources\MediaSchedules;

use App\Filament\Resources\MediaSchedules\Pages\CreateMediaSchedule;
use App\Filament\Resources\MediaSchedules\Pages\EditMediaSchedule;
use App\Filament\Resources\MediaSchedules\Pages\ListMediaSchedules;
use App\Filament\Resources\MediaSchedules\Schemas\MediaScheduleForm;
use App\Filament\Resources\MediaSchedules\Tables\MediaSchedulesTable;
use App\Models\MediaSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MediaScheduleResource extends Resource
{
    protected static ?string $model = MediaSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Zakazani termini';

    public static function form(Schema $schema): Schema
    {
        return MediaScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediaSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaSchedules::route('/'),
            'create' => CreateMediaSchedule::route('/create'),
            'edit' => EditMediaSchedule::route('/{record}/edit'),
        ];
    }
}
