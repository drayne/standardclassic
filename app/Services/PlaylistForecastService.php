<?php

namespace App\Services;

use App\Enums\TrackOrder;
use App\Models\Media;
use App\Models\MediaSchedule;
use App\Models\PlayedTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class PlaylistForecastService
{
    public const CROSSFADE_SECONDS = 3;

    public const HORIZON_HOURS = 72;

    /**
     * @return array{rows: array<int, array<string, mixed>>, playlist_rows: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    public function forecast(?Carbon $now = null, int $horizonHours = self::HORIZON_HOURS): array
    {
        $now ??= now(config('app.timezone'));
        $now = $now->copy()->setTimezone(config('app.timezone'));
        $horizon = $now->copy()->addHours($horizonHours);

        $playlist = Playlist::query()
            ->with(['media.type', 'media.composer'])
            ->where('active', true)
            ->first();

        $items = $playlist?->media ?? collect();
        $items = $items
            ->sortBy(fn (Media $media): string => sprintf(
                '%010d-%010d',
                (int) ($media->pivot?->sort_order ?? 0),
                (int) ($media->pivot?->id ?? 0),
            ))
            ->values();

        $schedules = MediaSchedule::query()
            ->with(['media.type', 'media.composer'])
            ->where('scheduled_at', '>', $now)
            ->where('scheduled_at', '<=', $horizon)
            ->orderBy('scheduled_at')
            ->orderBy('id')
            ->get();

        $state = $this->currentState($now);
        $currentPivotId = $this->cachePivotId($state['current_data']);
        $nextPivotId = $this->cachePivotId($state['next_data']);
        $bufferedPivotIds = array_values(array_unique(array_filter([
            $currentPivotId,
            $nextPivotId,
        ])));
        $rows = [];
        $warnings = [];

        if ($state['media']) {
            $currentRow = $this->mediaRow(
                $state['media'],
                $state['started_at'],
                $state['ends_at'],
                'current',
                'Trenutno u etru',
            );
            $currentRow['playlist_media_id'] = $currentPivotId;
            $rows[] = $currentRow;
        } else {
            $warnings[] = 'Nije moguće potvrditi trenutno emitovanu numeru.';
        }

        if (! $playlist || $items->isEmpty()) {
            $warnings[] = 'Nema aktivne plejliste ili je plejlista prazna.';

            return [
                'rows' => $this->appendFixedSchedules($rows, $schedules, $horizon),
                'playlist_rows' => [],
                'meta' => $this->meta($now, $horizon, $playlist?->name, $warnings),
            ];
        }

        $playlistIndex = $this->nextPlaylistIndex($items, $state['next_data'], $state['current_data']);
        $cursor = $now->copy();

        if ($state['ends_at']) {
            $cursor = $state['ends_at']->copy()->subSeconds(self::CROSSFADE_SECONDS);

            if ($schedules->first() && $schedules->first()->scheduled_at->lt($state['ends_at'])) {
                $rows[0]['ends_at'] = $schedules->first()->scheduled_at->copy();
                $rows[0]['status'] = 'interrupted';
                $cursor = $schedules->first()->scheduled_at->copy();
            }

            $cursor = $cursor->max($now);
        } elseif ($state['media']) {
            $warnings[] = 'Trajanje trenutno emitovane stavke nije poznato; procjena plejliste je zaustavljena.';

            return [
                'rows' => $this->appendFixedSchedules($rows, $schedules, $horizon),
                'playlist_rows' => $this->playlistRows($items, $rows, $bufferedPivotIds, $currentPivotId, $nextPivotId),
                'meta' => $this->meta($now, $horizon, $playlist->name, $warnings),
            ];
        }

        $scheduleIndex = 0;
        $blockedByUnknownDuration = false;
        $guard = 0;

        while ($cursor->lt($horizon) && $guard++ < 10000) {
            $schedule = $schedules->get($scheduleIndex);

            if ($schedule && $schedule->scheduled_at->lte($cursor)) {
                $scheduleRowIndex = count($rows);
                $rows[] = $this->scheduleRow($schedule);
                $scheduleIndex++;

                if ($this->durationSeconds($schedule->media) === null) {
                    $blockedByUnknownDuration = true;
                    break;
                }

                $scheduleEnd = $schedule->scheduled_at->copy()->addSeconds((int) $schedule->media->duration);
                $nextSchedule = $schedules->get($scheduleIndex);

                if ($nextSchedule && $nextSchedule->scheduled_at->lt($scheduleEnd)) {
                    $rows[$scheduleRowIndex]['ends_at'] = $nextSchedule->scheduled_at->copy();
                    $rows[$scheduleRowIndex]['status'] = 'interrupted';
                    $cursor = $nextSchedule->scheduled_at->copy();
                } else {
                    $cursor = $scheduleEnd->subSeconds(self::CROSSFADE_SECONDS);
                }

                continue;
            }

            /** @var Media $media */
            $media = $items->get($playlistIndex % $items->count());
            $start = $cursor->copy();
            $duration = $this->durationSeconds($media);
            $end = $duration !== null ? $start->copy()->addSeconds($duration) : null;
            $nextSchedule = $schedules->get($scheduleIndex);

            $row = $this->mediaRow($media, $start, $end, 'upcoming', 'Aktivna plejlista');
            $playlistMediaId = $media->pivot?->id ? (int) $media->pivot->id : null;
            $row['playlist_media_id'] = $playlistMediaId;

            if ($nextSchedule && $end && $nextSchedule->scheduled_at->lt($end)) {
                $row['ends_at'] = $nextSchedule->scheduled_at->copy();
                $row['status'] = 'interrupted';
            }

            $rows[] = $row;
            $playlistIndex++;

            if ($duration === null) {
                $blockedByUnknownDuration = true;
                break;
            }

            if ($nextSchedule && $end && $nextSchedule->scheduled_at->lt($end)) {
                $cursor = $nextSchedule->scheduled_at->copy();
            } else {
                $cursor = $end->copy()->subSeconds(self::CROSSFADE_SECONDS);
            }
        }

        if ($blockedByUnknownDuration) {
            $warnings[] = 'Dalji termini plejliste nisu procijenjeni jer jednoj stavci nedostaje trajanje.';
        }

        $followingPivotId = $this->followingBufferedPivotId($rows, $bufferedPivotIds);

        if ($followingPivotId !== null) {
            $bufferedPivotIds[] = $followingPivotId;
        }

        return [
            'rows' => $rows,
            'playlist_rows' => $this->playlistRows($items, $rows, $bufferedPivotIds, $currentPivotId, $nextPivotId, $followingPivotId),
            'meta' => $this->meta($now, $horizon, $playlist->name, $warnings),
        ];
    }

    /**
     * @param  Collection<int, Media>  $items
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, int>  $bufferedPivotIds
     * @return array<int, array<string, mixed>>
     */
    private function playlistRows(Collection $items, array $rows, array $bufferedPivotIds = [], ?int $currentPivotId = null, ?int $nextPivotId = null, ?int $followingPivotId = null): array
    {
        $forecastByPivot = collect($rows)
            ->filter(fn (array $row): bool => ! empty($row['playlist_media_id']))
            ->groupBy('playlist_media_id')
            ->map(fn (Collection $matches): array => $matches->first());

        $playlistRows = $items->values()->map(function (Media $media, int $index) use ($forecastByPivot, $bufferedPivotIds, $currentPivotId, $nextPivotId, $followingPivotId): array {
            $playlistMediaId = (int) ($media->pivot?->id ?? 0);
            $forecast = $forecastByPivot->get($playlistMediaId);
            $buffered = in_array($playlistMediaId, $bufferedPivotIds, true);
            $bufferedReason = match (true) {
                $playlistMediaId === $currentPivotId => 'current',
                $playlistMediaId === $nextPivotId => 'next',
                $playlistMediaId === $followingPivotId => 'following',
                default => null,
            };

            return [
                'position' => $index + 1,
                'playlist_media_id' => $playlistMediaId,
                'media_id' => $media->id,
                'title' => $media->title,
                'artist' => $media->artist,
                'type' => $media->type?->name,
                'duration' => $this->durationSeconds($media),
                'starts_at' => $forecast['starts_at'] ?? null,
                'ends_at' => $forecast['ends_at'] ?? null,
                'status' => $forecast['status'] ?? 'unforecasted',
                'interrupted' => ($forecast['status'] ?? null) === 'interrupted',
                'buffered' => $buffered,
                'buffered_reason' => $buffered ? $bufferedReason : null,
                'reorderable' => $playlistMediaId > 0 && ! $buffered,
            ];
        })->all();

        usort($playlistRows, function (array $left, array $right): int {
            $leftStart = $left['starts_at']?->getTimestamp() ?? PHP_INT_MAX;
            $rightStart = $right['starts_at']?->getTimestamp() ?? PHP_INT_MAX;

            return ($leftStart <=> $rightStart)
                ?: (($left['position'] ?? PHP_INT_MAX) <=> ($right['position'] ?? PHP_INT_MAX))
                ?: (($left['playlist_media_id'] ?? PHP_INT_MAX) <=> ($right['playlist_media_id'] ?? PHP_INT_MAX));
        });

        foreach ($playlistRows as $index => &$playlistRow) {
            $playlistRow['position'] = $index + 1;
        }
        unset($playlistRow);

        return $playlistRows;
    }

    /**
     * Find the first forecasted playlist item after the cache-backed buffered items.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, int>  $bufferedPivotIds
     */
    private function followingBufferedPivotId(array $rows, array $bufferedPivotIds): ?int
    {
        if ($bufferedPivotIds === []) {
            return null;
        }

        $hasKnownBufferedRow = collect($rows)
            ->contains(fn (array $row): bool => isset($row['playlist_media_id'])
                && in_array((int) $row['playlist_media_id'], $bufferedPivotIds, true));

        if (! $hasKnownBufferedRow) {
            return null;
        }

        foreach ($rows as $row) {
            $playlistMediaId = isset($row['playlist_media_id']) ? (int) $row['playlist_media_id'] : 0;

            if ($playlistMediaId > 0 && ! in_array($playlistMediaId, $bufferedPivotIds, true)) {
                return $playlistMediaId;
            }
        }

        return null;
    }

    /**
     * @return array{media: ?Media, started_at: ?Carbon, ends_at: ?Carbon, current_data: mixed, next_data: mixed}
     */
    private function currentState(Carbon $now): array
    {
        $currentData = Cache::get(TrackOrder::CURRENT_TRACK);
        $nextData = Cache::get(TrackOrder::NEXT_TRACK);
        $currentId = $this->cacheId($currentData);

        $latestPlayed = PlayedTrack::query()
            ->with(['media.type', 'media.composer'])
            ->latest('played_at')
            ->first();

        $playedForCurrent = $currentId
            ? PlayedTrack::query()
                ->with(['media.type', 'media.composer'])
                ->where('media_id', $currentId)
                ->latest('played_at')
                ->first()
            : $latestPlayed;

        $media = $currentId
            ? Media::with(['type', 'composer'])->find($currentId)
            : $latestPlayed?->media;

        if (! $media) {
            return [
                'media' => null,
                'started_at' => null,
                'ends_at' => null,
                'current_data' => $currentData,
                'next_data' => $nextData,
            ];
        }

        $startedAt = $playedForCurrent?->played_at?->copy()->setTimezone(config('app.timezone')) ?? $now->copy();
        $duration = $this->durationSeconds($media);
        $endsAt = $duration !== null
            ? $startedAt->copy()->addSeconds($duration)
            : null;

        if ($endsAt && $endsAt->lte($now)) {
            return [
                'media' => null,
                'started_at' => null,
                'ends_at' => null,
                'current_data' => $currentData,
                'next_data' => $nextData,
            ];
        }

        return [
            'media' => $media,
            'started_at' => $startedAt,
            'ends_at' => $endsAt,
            'current_data' => $currentData,
            'next_data' => $nextData,
        ];
    }

    private function nextPlaylistIndex(Collection $items, mixed $nextData, mixed $currentData): int
    {
        $nextPivotId = $this->cachePivotId($nextData);
        if ($nextPivotId) {
            $index = $items->search(fn (Media $media): bool => (int) ($media->pivot?->id) === $nextPivotId);
            if ($index !== false) {
                return (int) $index;
            }
        }

        $currentPivotId = $this->cachePivotId($currentData);
        if ($currentPivotId) {
            $index = $items->search(fn (Media $media): bool => (int) ($media->pivot?->id) === $currentPivotId);
            if ($index !== false) {
                return ((int) $index + 1) % $items->count();
            }
        }

        $currentId = $this->cacheId($currentData);
        if ($currentId) {
            $index = $items->search(fn (Media $media): bool => (int) $media->id === $currentId);
            if ($index !== false) {
                return ((int) $index + 1) % $items->count();
            }
        }

        return 0;
    }

    private function cacheId(mixed $data): ?int
    {
        $id = is_array($data) ? ($data['id'] ?? null) : ($data?->id ?? null);

        return $id ? (int) $id : null;
    }

    private function cachePivotId(mixed $data): ?int
    {
        $id = is_array($data) ? ($data['pivot_id'] ?? null) : ($data?->pivot_id ?? null);

        return $id ? (int) $id : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function mediaRow(Media $media, ?Carbon $startsAt, ?Carbon $endsAt, string $status, string $source): array
    {
        return [
            'kind' => $status === 'current' ? 'current' : 'playlist',
            'status' => $status,
            'source' => $source,
            'media_id' => $media->id,
            'title' => $media->title,
            'artist' => $media->artist,
            'type' => $media->type?->name,
            'duration' => $this->durationSeconds($media),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'schedule_id' => null,
            'playlist_media_id' => $media->pivot?->id ? (int) $media->pivot->id : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function scheduleRow(MediaSchedule $schedule): array
    {
        $media = $schedule->media;
        $duration = $this->durationSeconds($media);

        return [
            'kind' => 'scheduled',
            'status' => 'scheduled',
            'source' => 'Zakazana emisija',
            'media_id' => $media?->id,
            'title' => $media?->title ?? 'Nepoznata emisija',
            'artist' => $media?->artist,
            'type' => $media?->type?->name,
            'duration' => $duration,
            'starts_at' => $schedule->scheduled_at->copy()->setTimezone(config('app.timezone')),
            'ends_at' => $duration !== null
                ? $schedule->scheduled_at->copy()->setTimezone(config('app.timezone'))->addSeconds($duration)
                : null,
            'schedule_id' => $schedule->id,
            'playlist_media_id' => null,
        ];
    }

    private function appendFixedSchedules(array $rows, Collection $schedules, Carbon $horizon): array
    {
        foreach ($schedules as $schedule) {
            $row = $this->scheduleRow($schedule);
            if ($row['starts_at']->lte($horizon)) {
                $rows[] = $row;
            }
        }

        usort($rows, fn (array $a, array $b): int => ($a['starts_at']?->getTimestamp() ?? PHP_INT_MAX) <=> ($b['starts_at']?->getTimestamp() ?? PHP_INT_MAX));

        return $rows;
    }

    /**
     * @param  array<int, string>  $warnings
     * @return array<string, mixed>
     */
    private function meta(Carbon $now, Carbon $horizon, ?string $playlistName, array $warnings): array
    {
        return [
            'now' => $now,
            'horizon' => $horizon,
            'playlist_name' => $playlistName,
            'warnings' => array_values(array_unique($warnings)),
            'crossfade_seconds' => self::CROSSFADE_SECONDS,
        ];
    }

    private function durationSeconds(?Media $media): ?int
    {
        if (! $media || $media->duration === null || (int) $media->duration <= 0) {
            return null;
        }

        return (int) $media->duration;
    }
}
