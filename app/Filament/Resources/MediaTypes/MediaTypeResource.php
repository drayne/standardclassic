<?php

namespace App\Filament\Resources\MediaTypes;

use App\Filament\Resources\MediaTypes\Pages\CreateMediaType;
use App\Filament\Resources\MediaTypes\Pages\EditMediaType;
use App\Filament\Resources\MediaTypes\Pages\ListMediaTypes;
use App\Filament\Resources\MediaTypes\Pages\ViewMediaType;
use App\Filament\Resources\MediaTypes\Schemas\MediaTypeForm;
use App\Filament\Resources\MediaTypes\Schemas\MediaTypeInfolist;
use App\Filament\Resources\MediaTypes\Tables\MediaTypesTable;
use App\Models\MediaType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MediaTypeResource extends Resource
{
    protected static ?string $model = MediaType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Type';

    public static function form(Schema $schema): Schema
    {
        return MediaTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MediaTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediaTypesTable::configure($table);
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
            'index' => ListMediaTypes::route('/'),
            'create' => CreateMediaType::route('/create'),
            'view' => ViewMediaType::route('/{record}'),
            'edit' => EditMediaType::route('/{record}/edit'),
        ];
    }
}
