<?php

namespace App\Filament\Resources\Episodes;

use App\Models\Episode;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Facades\Storage;

class EpisodeResource extends Resource
{
    protected static ?string $model = Episode::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Radio';

    protected static ?string $modelLabel = 'Epizoda';

    protected static ?string $pluralModelLabel = 'Epizode';

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('datum')
                    ->label('Datum')
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->default(now())
                    ->required(),
                Select::make('podcast_id')
                    ->label('Podkast')
                    ->relationship('podcast', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('show_id')
                    ->label('Emisija')
                    ->relationship('show', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('media_id')
                    ->label('Medij (iz naše baze)')
                    ->relationship('media', 'title')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->disabled(fn (callable $get) => filled($get('external_url'))),
                TextInput::make('external_url')
                    ->label('YouTube link / Vanjski URL')
                    ->url()
                    ->maxLength(255)
                    ->live()
                    ->disabled(fn (callable $get) => filled($get('media_id'))),
                Textarea::make('description')
                    ->label('Opis')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Naslov')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datum')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('podcast.name')
                    ->label('Podkast')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('show.name')
                    ->label('Emisija')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('media.title')
                    ->label('Medij')
                    ->placeholder('Nije povezano')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('podcast')
                    ->relationship('podcast', 'name')
                    ->label('Podkast'),
                Tables\Filters\SelectFilter::make('show')
                    ->relationship('show', 'name')
                    ->label('Emisija'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Izmijeni epizodu')
                    ->modalSubmitActionLabel('Sačuvaj')
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Izmijeni')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->iconButton()
                    ->tooltip('Izbriši')
                    ->color('danger')
                    ->action(fn ($record) => $record->delete()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Nema epizoda')
            ->emptyStateDescription('Dodajte epizode');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
        ];
    }
}
