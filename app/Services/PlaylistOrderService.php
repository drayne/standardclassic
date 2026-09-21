<?php

namespace App\Services;

use App\Models\Playlist;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

final class PlaylistOrderService
{
    public function moveBefore(int $playlistMediaId, int $targetPlaylistMediaId, bool $before): void
    {
        $playlistId = Playlist::query()
            ->where('active', true)
            ->value('id');

        if (! $playlistId) {
            throw (new ModelNotFoundException)->setModel(Playlist::class);
        }

        $pivotIds = DB::table('playlist_media')
            ->where('playlist_id', $playlistId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $currentPosition = array_search($playlistMediaId, $pivotIds, true);
        $targetPosition = array_search($targetPlaylistMediaId, $pivotIds, true);

        if ($currentPosition === false || $targetPosition === false) {
            throw (new ModelNotFoundException)->setModel('playlist_media', [$playlistMediaId]);
        }

        if ($currentPosition === $targetPosition) {
            return;
        }

        array_splice($pivotIds, $currentPosition, 1);
        $targetPosition = array_search($targetPlaylistMediaId, $pivotIds, true);
        $insertPosition = $before ? $targetPosition : $targetPosition + 1;
        array_splice($pivotIds, $insertPosition, 0, [$playlistMediaId]);

        DB::transaction(function () use ($playlistId, $pivotIds): void {
            foreach ($pivotIds as $position => $pivotId) {
                DB::table('playlist_media')
                    ->where('playlist_id', $playlistId)
                    ->where('id', $pivotId)
                    ->update(['sort_order' => $position + 1]);
            }
        });
    }
}
