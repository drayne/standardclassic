<?php

namespace App\Filament\Widgets;

use App\Models\PlayedTrack;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class LatestPlayedTracksWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected function getTableContentHeight(): ?string
    {
        return '450px';
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->query(
                PlayedTrack::query()
                    ->with(['media', 'media.type'])
                    ->latest('played_at')
                    ->limit(5)
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
                        Tables\Columns\TextColumn::make('played_at')
                            ->since()
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-m-play')
                            ->iconColor('danger')
                            ->alignEnd()
                            ->action(
                                Action::make('play_column')
                                    ->modalHeading(fn ($record) => "Preslušavanje: {$record->media->title}")
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Zatvori')
                                    ->modalContent(fn ($record) => new HtmlString(
                                        Blade::render('
                                            <div class="flex flex-col items-center justify-center p-4">
                                                <audio controls autoplay class="w-full">
                                                    <source src="{{ route(\'media.stream\', $record->media) }}" type="audio/mpeg">
                                                    Vaš pretraživač ne podržava audio element.
                                                </audio>
                                            </div>
                                        ', ['record' => $record])
                                    )),
                            ),
                    ])->grow(false),
                ]),
            ])
            ->paginated(false)
            ->header(null)
            ->heading('Poslednje reprodukovano')
            ->emptyStateHeading('Nema reprodukovanih')
            ->extraAttributes([
                'class' => 'h-full flex-1 flex flex-col [&_div.fi-ta-content]:flex-1 [&_div.fi-ta-content]:flex [&_div.fi-ta-content]:flex-col [&_div.fi-ta-ctn]:flex-1 [&_div.fi-ta-ctn]:flex [&_div.fi-ta-ctn]:flex-col',
            ])
            ->contentGrid([
                'default' => 1,
            ]);
    }
}
