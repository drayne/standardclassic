<?php

namespace App\Filament\Resources\Playlists\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Mediji na plejlisti';

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->recordTitleAttribute('title')

            ->columns([
                Tables\Columns\ImageColumn::make('composer.image')
                    ->label('Slika kompozitora')
                    ->disk('composer-images')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF&format=svg&icon=heroicon-s-musical-note')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('artist')
                    ->label('Izvođač')
                    ->searchable()
                    ->sortable(),
            ])

            ->headerActions([
                AttachAction::make()
                    ->label('Dodaj na plejlistu')
                    ->modalWidth('xl')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->extraAttributes(['style' => 'margin-bottom: 60px']),
                        Forms\Components\Checkbox::make('hide_duplicates')
                            ->label('Sakrij duplikate (već dodate)')
                            ->default(true)
                            ->live(),
                    ])
                    ->recordSelectOptionsQuery(function ($query, AttachAction $action) {
                        $hideDuplicates = $action->getRawFormData()['hide_duplicates'] ?? true;
                        if ($hideDuplicates) {
                            $query->whereNotIn('media.id', fn ($subquery) => $subquery->select('media_id')
                                ->from('playlist_media')
                                ->where('playlist_id', $this->getOwnerRecord()->getKey()));
                        }
                        return $query;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $maxSortOrder = DB::table('playlist_media')
                            ->where('playlist_id', $this->getOwnerRecord()->getKey())
                            ->max('sort_order');

                        $data['sort_order'] = ($maxSortOrder ?? 0) + 1;

                        return $data;
                    }),
            ])

            ->actions([
                DetachAction::make()
                    ->label('Izbaci')
                    ->modalHeading(fn ($record) => "Izbaci " . ($record->title ?? 'medij'))
                    ->modalSubmitActionLabel('Izbaci')
                    ->successNotificationTitle('Izbačeno')

                    // 🔥 PRECIZNO brisanje JEDNOG pivot reda
                    ->action(function ($record) {
                        $pivotId = $record->pivot?->id;

                        if ($pivotId) {
                            \App\Models\PlaylistMedia::query()
                                ->where('id', $pivotId)
                                ->delete();
                        }
                    })
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Izbaci')
                        ->modalHeading('Izbaci izabrane medije')
                        ->modalSubmitActionLabel('Izbaci')
                        ->successNotificationTitle('Izbačeno')
                        ->fetchSelectedRecords()

                        ->using(function (RelationManager $livewire, EloquentCollection $records) {
                            $playlistId = $livewire->getOwnerRecord()->getKey();

                            foreach ($records as $record) {
                                DB::table('playlist_media')
                                    ->where('playlist_id', $playlistId)
                                    ->where('media_id', $record->getKey())
                                    ->limit(1) // 🔥 KLJUČNO: briše samo jedan pivot red
                                    ->delete();
                            }
                        }),
                ]),
            ])

            ->reorderable('sort_order'); // radi jer imamo pivot kolonu + orderBy
    }
}
