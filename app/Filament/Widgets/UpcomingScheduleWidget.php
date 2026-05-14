<?php

namespace App\Filament\Widgets;

use App\Models\MediaSchedule;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingScheduleWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    protected function getTableRecordAction(): ?string
    {
        return null;
    }

    public function table(Table $table): Table
    {
        $totalUpcoming = MediaSchedule::query()
            ->where('played', false)
            ->where('scheduled_at', '>', now())
            ->count();

        return $table
            ->poll('15s')
            ->query(
                MediaSchedule::query()
                    ->with(['media', 'media.type', 'media.composer'])
                    ->where('played', false)
                    ->where('scheduled_at', '>', now())
                    ->orderBy('scheduled_at', 'asc')
                    ->limit(4)
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('media.composer.image')
                        ->circular()
                        ->defaultImageUrl(url('https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF&format=svg&icon=heroicon-s-musical-note'))
                        ->disk('composer-images')
                        ->grow(false),
                    Tables\Columns\IconColumn::make('media.type.name')
                        ->label('Tip')
                        ->icon(fn (string $state): string => match (strtolower($state)) {
                            'song' => 'heroicon-o-musical-note',
                            'show' => 'heroicon-o-microphone',
                            'podcast' => 'heroicon-o-megaphone',
                            default => 'heroicon-o-question-mark-circle',
                        })
                        ->color(fn (string $state): string => match (strtolower($state)) {
                            'song' => 'primary',
                            'show' => 'success',
                            'podcast' => 'warning',
                            default => 'gray',
                        })
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('media.title')
                            ->weight('bold')
                            ->color('slate-900')
                            ->size('sm'),
                        Tables\Columns\TextColumn::make('media.artist')
                            ->color('gray-500')
                            ->size('xs'),
                    ])->space(1),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('scheduled_at')
                            ->formatStateUsing(fn ($state) => $state->format('H:i'))
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-clock')
                            ->alignEnd(),
                        Tables\Columns\TextColumn::make('scheduled_at_date')
                            ->state(fn ($record) => $record->scheduled_at->format('d.m.Y'))
                            ->color('gray-400')
                            ->size('xs')
                            ->alignEnd(),
                    ])->grow(false),
                ]),
            ])
            ->paginated(false)
            ->header(null)
            ->heading(new \Illuminate\Support\HtmlString('Predstojeće zakazane emisije <span class="text-xs font-normal text-gray-500">(ukupno zakazanih: ' . $totalUpcoming . ')</span>'))
            ->headerActions([
                Action::make('view_all')
                    ->label('Vidi sve')
                    ->url(\App\Filament\Resources\MediaSchedules\MediaScheduleResource::getUrl('index'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->size('xs')
                    ->color('gray'),
            ])
            ->emptyStateHeading('Nema zakazanih emisija')
            ->emptyStateDescription('Trenutno nema emisija u rasporedu.')
            ->extraAttributes([
                'class' => 'h-full flex-1 flex flex-col [&_div.fi-ta-content]:flex-1 [&_div.fi-ta-content]:flex [&_div.fi-ta-content]:flex-col [&_div.fi-ta-ctn]:flex-1 [&_div.fi-ta-ctn]:flex [&_div.fi-ta-ctn]:flex-col',
            ])
            ->contentGrid([
                'default' => 1,
            ])
            ->deferLoading();
    }
}
