<?php

use App\Enums\TrackOrder;
use App\Models\Media;
use App\Models\MediaSchedule;
use App\Models\MediaType;
use App\Models\PlayedTrack;
use App\Models\Playlist;
use App\Models\TrackType;
use App\Models\User;
use App\Services\PlaylistForecastService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    Carbon::setTestNow(Carbon::parse('2026-09-21 12:00:00', config('app.timezone')));
    Cache::flush();
});

afterEach(function (): void {
    Carbon::setTestNow();
});

function forecastMedia(string $title, int|string|null $duration, string $type = 'song'): Media
{
    $mediaType = MediaType::where('name', $type)->firstOrFail();

    return Media::create([
        'media_type_id' => $mediaType->id,
        'title' => $title,
        'artist' => 'Test artist',
        'file_path' => "audio/{$title}.mp3",
        'image_path' => null,
        'duration' => $duration,
    ]);
}

function attachForecastMedia(Playlist $playlist, Media $media, int $sortOrder): int
{
    $playlist->media()->attach($media->id, ['sort_order' => $sortOrder]);

    return (int) $playlist->media()->whereKey($media->id)->firstOrFail()->pivot->id;
}

test('forecast applies the three second crossfade and scheduled interruption', function (): void {
    $playlist = Playlist::create(['name' => 'Test playlist', 'active' => true]);
    $current = forecastMedia('Current', 100);
    $next = forecastMedia('Next', 100);
    $show = forecastMedia('Scheduled show', 60, 'show');

    $currentPivotId = attachForecastMedia($playlist, $current, 1);
    $nextPivotId = attachForecastMedia($playlist, $next, 2);

    $trackType = TrackType::firstOrCreate(['name' => 'regular']);
    PlayedTrack::create([
        'media_id' => $current->id,
        'track_type_id' => $trackType->id,
        'played_at' => Carbon::parse('2026-09-21 11:59:30', config('app.timezone')),
    ]);

    Cache::put(TrackOrder::CURRENT_TRACK, [
        'id' => $current->id,
        'pivot_id' => $currentPivotId,
    ]);
    Cache::put(TrackOrder::NEXT_TRACK, [
        'id' => $next->id,
        'pivot_id' => $nextPivotId,
    ]);

    MediaSchedule::create([
        'media_id' => $show->id,
        'scheduled_at' => Carbon::parse('2026-09-21 12:00:30', config('app.timezone')),
        'played' => true,
    ]);

    $forecast = app(PlaylistForecastService::class)->forecast(
        Carbon::parse('2026-09-21 12:00:00', config('app.timezone')),
        1,
    );

    expect(count($forecast['rows']))->toBeGreaterThan(3)
        ->and($forecast['rows'][0]['status'])->toBe('interrupted')
        ->and($forecast['rows'][0]['ends_at']->format('H:i:s'))->toBe('12:00:30')
        ->and($forecast['rows'][1]['kind'])->toBe('scheduled')
        ->and($forecast['rows'][1]['starts_at']->format('H:i:s'))->toBe('12:00:30')
        ->and($forecast['rows'][2]['title'])->toBe('Next')
        ->and($forecast['rows'][2]['starts_at']->format('H:i:s'))->toBe('12:01:27');
});

test('forecast stops playlist timing after a media item without duration', function (): void {
    $playlist = Playlist::create(['name' => 'Test playlist', 'active' => true]);
    $current = forecastMedia('Current', 60);
    $unknown = forecastMedia('Unknown duration', null);
    $following = forecastMedia('Following', 60);

    $currentPivotId = attachForecastMedia($playlist, $current, 1);
    $unknownPivotId = attachForecastMedia($playlist, $unknown, 2);
    attachForecastMedia($playlist, $following, 3);

    $trackType = TrackType::firstOrCreate(['name' => 'regular']);
    PlayedTrack::create([
        'media_id' => $current->id,
        'track_type_id' => $trackType->id,
        'played_at' => Carbon::parse('2026-09-21 11:59:30', config('app.timezone')),
    ]);

    Cache::put(TrackOrder::CURRENT_TRACK, [
        'id' => $current->id,
        'pivot_id' => $currentPivotId,
    ]);
    Cache::put(TrackOrder::NEXT_TRACK, [
        'id' => $unknown->id,
        'pivot_id' => $unknownPivotId,
    ]);

    $forecast = app(PlaylistForecastService::class)->forecast(
        Carbon::parse('2026-09-21 12:00:00', config('app.timezone')),
        1,
    );

    expect($forecast['rows'])->toHaveCount(2)
        ->and($forecast['rows'][1]['title'])->toBe('Unknown duration')
        ->and($forecast['rows'][1]['duration'])->toBeNull()
        ->and($forecast['meta']['warnings'])->toContain('Dalji termini plejliste nisu procijenjeni jer jednoj stavci nedostaje trajanje.');
});

test('admin program page renders the forecast', function (): void {
    $playlist = Playlist::create(['name' => 'Visual test playlist', 'active' => true]);
    foreach ([
        ['Song', 'song'],
        ['Show', 'show'],
        ['Podcast', 'podcast'],
        ['Jingle', 'jingle'],
    ] as $index => [$title, $type]) {
        attachForecastMedia($playlist, forecastMedia($title, 3600, $type), $index + 1);
    }

    $scheduledShow = forecastMedia('Scheduled visual show', 60, 'show');
    MediaSchedule::create([
        'media_id' => $scheduledShow->id,
        'scheduled_at' => Carbon::parse('2026-09-21 12:10:00', config('app.timezone')),
        'played' => false,
    ]);

    $user = User::factory()->create(['email' => 'forecast-admin@example.com']);
    config(['auth.allowed_admin_emails' => $user->email]);

    $response = $this->actingAs($user)->get('/admin/program');

    $response->assertOk()
        ->assertSee('Predstojeći program')
        ->assertSee('Regularna stavka')
        ->assertSee('Zakazana emisija')
        ->assertSee('Pjesma')
        ->assertSee('Emisija')
        ->assertSee('Podkast')
        ->assertSee('Džingl')
        ->assertSee('Počinje')
        ->assertSee('Do');
});
