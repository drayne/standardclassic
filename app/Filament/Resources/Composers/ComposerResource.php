<?php

namespace App\Filament\Resources\Composers;

use App\Filament\Resources\Composers\Pages\CreateComposer;
use App\Filament\Resources\Composers\Pages\EditComposer;
use App\Filament\Resources\Composers\Pages\ListComposers;
use App\Models\Composer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComposerResource extends Resource
{
    protected static ?string $model = Composer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static string|null|\UnitEnum $navigationGroup = 'Portal';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Kompozitori';

    protected static ?string $pluralLabel = 'Kompozitori';

    protected static ?string $modelLabel = 'kompozitor';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Osnovni podaci')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Ime i prezime')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('URL (npr. Wikipedia)')
                            ->url()
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->label('Slika')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                                '3:4',
                            ])
                            ->disk('composer-images')
                            ->directory('composers')
                            ->columnSpanFull(),
                    ]),

                Section::make('Prevodi opisa')
                    ->description('Dodajte kratak opis ispod slike na različitim jezicima.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('translations')
                            ->hiddenLabel()
                            ->label(false)
                            ->relationship()
                            ->schema([
                                Select::make('language_id')
                                    ->label('Jezik')
                                    ->relationship('language', 'name')
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                Textarea::make('description')
                                    ->label('Opis')
                                    ->rows(5)
                                    ->required(),
                            ])
                            ->columns(1)
                            ->maxItems(fn () => \App\Models\Language::count())
                            ->addActionLabel('Dodaj prevod')
                            ->itemLabel(fn (array $state): ?string => \App\Models\Language::find($state['language_id'] ?? null)?->name ?? 'Novi prevod'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Slika')
                    ->disk('composer-images')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Ime i prezime')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(30),
                TextColumn::make('created_at')
                    ->label('Kreirano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->emptyStateHeading('Nema kompozitora')
            ->filters([
                //
            ])
            ->recordActions([
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComposers::route('/'),
            'create' => CreateComposer::route('/create'),
            'edit' => EditComposer::route('/{record}/edit'),
        ];
    }
}
