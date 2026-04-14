<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use Filament\Widgets\TableWidget as BaseWidget;

class ActivityLogWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 2;

    protected static ?string $heading = 'Aktivnosti korisnika';

    protected function getTableContentHeight(): ?string
    {
        return '450px';
    }

    public static function canView(): bool
    {
        try {
            return Schema::hasTable('activity_logs');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()->with('user')->latest()->limit(7)
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('user.name')
                        ->label('Korisnik')
                        ->placeholder('Sistem')
                        ->weight('bold')
                        ->size('xs')
                        ->icon('heroicon-m-user')
                        ->color('primary')
                        ->extraAttributes(['class' => 'min-w-[120px] pr-4'])
                        ->grow(false),
                    Tables\Columns\TextColumn::make('description')
                        ->label('Aktivnost')
                        ->size('xs')
                        ->weight('normal')
                        ->color('gray-600'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('subject_type')
                            ->label('Resurs')
                            ->size('xs')
                            ->color('gray-500')
                            ->formatStateUsing(function (string $state): string {
                                $resource = str_replace('App\\Models\\', '', $state);
                                $translated = match ($resource) {
                                    'Media' => 'Medij',
                                    'MediaSchedule' => 'Zakazana emisija',
                                    'Playlist' => 'Plejlista',
                                    'User' => 'Korisnik',
                                    default => $resource,
                                };
                                return '📍 ' . $translated;
                            }),
                        Tables\Columns\TextColumn::make('created_at')
                            ->label('Vrijeme')
                            ->dateTime('H:i:s (d.m.Y)')
                            ->size('xs')
                            ->color('gray-400')
                            ->icon('heroicon-m-clock'),
                    ])->alignEnd()->grow(false),
                ])->from('md'),
            ])
            ->paginated(false)
            // ->header(null)
            ->headerActions([
                \Filament\Actions\Action::make('viewAll')
                    ->label('Vidi sve')
                    ->url(fn (): string => \App\Filament\Resources\ActivityLogs\ActivityLogResource::getUrl())
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->size('xs')
                    ->color('gray'),
            ])
            ->extraAttributes([
                'class' => 'h-full flex-1 flex flex-col [&_div.fi-ta-content]:flex-1 [&_div.fi-ta-content]:flex [&_div.fi-ta-content]:flex-col [&_div.fi-ta-ctn]:flex-1 [&_div.fi-ta-ctn]:flex [&_div.fi-ta-ctn]:flex-col',
            ])
            ->recordClasses(['py-0 border-b border-gray-100 dark:border-gray-800']);
    }
}
