<?php

namespace App\Filament\Resources\CoverImages;

use App\Filament\Resources\CoverImages\Pages\ManageCoverImages;
use App\Models\CoverImage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoverImageResource extends Resource
{
    protected static ?string $model = CoverImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|null|\UnitEnum $navigationGroup = 'Portal';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Naslovne slike';

    protected static ?string $pluralLabel = 'Naslovne slike';

    protected static ?string $modelLabel = 'Naslovna slika';

    protected static ?string $recordTitleAttribute = 'path';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Slika')
                    ->image()
                    ->disk('cover-images')
                    ->required(),
                Select::make('position')
                    ->label('Pozicija')
                    ->options([
                        'L' => 'Lijevo',
                        'R' => 'Desno',
                    ])
                    ->unique(ignoreRecord: true)
                    ->placeholder('Nije prikazana'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')
                    ->label('Slika')
                    ->disk('cover-images'),
                TextColumn::make('position')
                    ->label('Pozicija')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'L' => 'Lijevo',
                        'R' => 'Desno',
                        default => 'Nije prikazana',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Kreirano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Izmijenjeno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Izmijeni')
                    ->modalHeading('Izmijeni naslovnu sliku')
                    ->modalSubmitActionLabel('Sačuvaj')
                    ->color('warning'),
                DeleteAction::make(),
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
            'index' => ManageCoverImages::route('/'),
        ];
    }
}
