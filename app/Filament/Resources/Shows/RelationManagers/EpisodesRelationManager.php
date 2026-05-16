<?php

namespace App\Filament\Resources\Shows\RelationManagers;

use Filament\Schemas\Schema;
use Livewire\Attributes\On;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    #[On('refresh_episodes')]
    public function refresh(): void
    {
        $this->dispatch('$refresh');
    }

    protected static ?string $title = 'Epizode';

    protected static ?string $modelLabel = 'Epizoda';

    protected static ?string $pluralModelLabel = 'Epizode';

    public function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return true;
    }

    public function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Opis')
                    ->rows(3),
                DatePicker::make('datum')
                    ->label('Datum')
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->default(now())
                    ->required(),
                Select::make('media_id')
                    ->label('Medij (emisija iz naše baze)')
                    ->relationship(
                        name: 'media',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->whereHas('type', fn ($query) => $query->where('name', 'show')),
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->disabled(fn (callable $get) => filled($get('external_url')))
                    ->requiredWithout('external_url')
                    ->prohibitedIf('external_url', fn ($state, $get) => filled($get('external_url'))),
                TextInput::make('external_url')
                    ->label('YouTube link')
                    ->url()
                    ->maxLength(255)
                    ->live()
                    ->disabled(fn (callable $get) => filled($get('media_id')))
                    ->requiredWithout('media_id')
                    ->prohibitedIf('media_id', fn ($state, $get) => filled($get('media_id'))),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Naslov')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datum')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('media.title')
                    ->label('Medij')
                    ->placeholder('Nije povezano'),
                Tables\Columns\TextColumn::make('external_url')
                    ->label('YouTube link')
                    ->limit(30)
                    ->placeholder('Nema'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Nova epizoda')
                    ->modalSubmitActionLabel('Dodaj'),
            ])
            ->recordActions([
                Action::make('izmjeni')
                    ->label('Izmijeni')
                    ->modalHeading('Izmijeni epizodu')
                    ->modalSubmitActionLabel('Sačuvaj')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->mountUsing(fn (Schema $schema, $record) => $schema->fill($record->toArray()))
                    ->action(function ($record, array $data): void {
                        $record->update($data);
                    })
                    ->form(fn (Schema $schema) => $this->form($schema)),
                Action::make('obrisi')
                    ->color('danger')
                    ->label('Obriši')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
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
}
