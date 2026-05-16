<?php

namespace App\Filament\Resources\Shows;

use App\Filament\Resources\Shows\Pages\ListShows;
use App\Models\Show;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ShowResource extends Resource
{
    protected static ?string $model = Show::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Portal';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Emisija';

    protected static ?string $pluralModelLabel = 'Emisije';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRadio;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    ImageColumn::make('image')
                        ->default(fn ($record) => match($record->name) {
                            'Kult Express' => asset('images/logo-kult-express.jpg'),
                            'Popodne sa Acom Informacijom' => asset('images/popodne-sa-acom-informacijom.jpg'),
                            default => asset('images/emisije-na-nasem-radiju.jpg'),
                        })
                        ->height(300)
                        ->width('100%')
                        ->extraImgAttributes(['class' => 'object-cover rounded-xl']),
                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('name')
                            ->weight('bold')
                            ->size('lg'),
                        TextColumn::make('episodes_count')
                            ->counts('episodes')
                            ->formatStateUsing(fn ($state) => "{$state} epizoda")
                            ->color('gray')
                            ->size('sm'),
                    ])->space(1)->extraAttributes(['class' => 'mt-2 pb-2']),
                ])->extraAttributes(['class' => 'bg-transparent shadow-none ring-0'])
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->extraAttributes([
                'class' => 'bg-transparent shadow-none ring-0 border-none opacity-100 filament-tables-table-container',
            ])
            ->recordUrl(fn (Model $record): string => Pages\ViewShow::getUrl([$record]))
            ->actions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EpisodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShows::route('/'),
            'view' => Pages\ViewShow::route('/{record}'),
        ];
    }
}
