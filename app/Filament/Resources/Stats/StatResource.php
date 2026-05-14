<?php

namespace App\Filament\Resources\Stats;

use App\Filament\Resources\Stats\Pages\ManageStats;
use App\Filament\Widgets\ListenersChart;
use App\Filament\Widgets\StatTableWidget;
use App\Models\Stat;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatResource extends Resource
{
    protected static ?string $model = Stat::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Statistika';

    protected static ?string $pluralLabel = 'Statistika slušalaca';

    protected static ?string $modelLabel = 'Statistika';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Read-only
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([])
            ->emptyState(null)
            ->paginated(false);
    }

    public static function getWidgets(): array
    {
        return [
            ListenersChart::class,
            StatTableWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStats::route('/'),
        ];
    }
}
