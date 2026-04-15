<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('type.name')
                    ->label('Tip')
                    ->tooltip(fn (string $state): string => match (strtolower($state)) {
                        'song' => 'Pjesma',
                        'show' => 'Emisija',
                        'podcast' => 'Podkast',
                        'jingle' => 'Džingl',
                        default => $state,
                    })
                    ->icon(fn (string $state): string => match (strtolower($state)) {
                        'song' => 'heroicon-o-musical-note',
                        'show' => 'heroicon-o-microphone',
                        'podcast' => 'heroicon-o-megaphone',
                        'jingle' => 'heroicon-o-sparkles',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'song' => 'primary',
                        'show' => 'success',
                        'podcast' => 'warning',
                        'jingle' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Naziv')
                    ->searchable(),
                TextColumn::make('composer.name')
                    ->label('Kompozitor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('artist')
                    ->label('Izvođač')
                    ->searchable(),
                TextColumn::make('duration')
                    ->label('Trajanje')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('play')
                    ->label('')
                    ->icon('heroicon-o-play-circle')
                    ->color('primary')
                    ->tooltip('Preslušaj')
                    ->iconButton()
                    ->modalHeading(fn ($record) => "Preslušavanje: {$record->title}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Zatvori')
                    ->modalContent(fn ($record) => new HtmlString(
                        Blade::render('
                            <div class="flex flex-col items-center justify-center p-4">
                                <audio controls autoplay class="w-full">
                                    <source src="{{ route(\'media.stream\', $record) }}" type="audio/mpeg">
                                    Vaš pretraživač ne podržava audio element.
                                </audio>
                            </div>
                        ', ['record' => $record])
                    )),
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Detalji')
                    ->color('info'),
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Izmijeni')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->iconButton()
                    ->tooltip('Izbriši')
                    ->color('danger'),
            ])
            ->columnToggleFormColumns(0)
            ->bulkActions([
                DeleteBulkAction::make()->label('Izbriši izabrane'),
            ]);
    }
}
