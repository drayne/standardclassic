<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    protected static ?string $navigationLabel = 'Aktivnosti';

    protected static ?string $pluralModelLabel = 'Aktivnosti';

    protected static ?string $modelLabel = 'Aktivnost';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Vrijeme')
                    ->dateTime('H:i:s (d.m.Y)')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Korisnik')
                    ->placeholder('Sistem')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Aktivnost')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Resurs')
                    ->formatStateUsing(function (string $state): string {
                        $resource = str_replace('App\\Models\\', '', $state);
                        return match ($resource) {
                            'Media' => 'Medij',
                            'MediaSchedule' => 'Zakazana emisija',
                            'Playlist' => 'Plejlista',
                            'User' => 'Korisnik',
                            default => $resource,
                        };
                    })
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Filtriraj po korisniku')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('subject_type')
                    ->label('Filtriraj po resursu')
                    ->options(function () {
                        return ActivityLog::query()
                            ->distinct()
                            ->pluck('subject_type')
                            ->mapWithKeys(function ($type) {
                                $resource = str_replace('App\\Models\\', '', $type);
                                $translated = match ($resource) {
                                    'Media' => 'Medij',
                                    'MediaSchedule' => 'Zakazana emisija',
                                    'Playlist' => 'Plejlista',
                                    'User' => 'Korisnik',
                                    default => $resource,
                                };
                                return [$type => $translated];
                            })
                            ->toArray();
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}
