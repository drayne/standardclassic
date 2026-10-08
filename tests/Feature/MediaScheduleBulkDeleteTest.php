<?php

use App\Filament\Resources\MediaSchedules\Pages\ListMediaSchedules;
use App\Models\Media;
use App\Models\MediaSchedule;
use App\Models\MediaType;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use Livewire\Livewire;

function scheduleFor(string $title, string $at): MediaSchedule
{
    $media = Media::create([
        'media_type_id' => MediaType::where('name', 'song')->firstOrFail()->id,
        'title' => $title,
        'artist' => 'Test artist',
        'file_path' => "audio/{$title}.mp3",
    ]);

    return MediaSchedule::create(['media_id' => $media->id, 'scheduled_at' => $at]);
}

test('admin can bulk delete selected media schedules', function (): void {
    $user = User::factory()->create(['email' => 'schedule-admin@example.com']);
    config(['auth.allowed_admin_emails' => $user->email]);
    $this->actingAs($user);

    $first = scheduleFor('Prva', '2026-10-10 10:00:00');
    $second = scheduleFor('Druga', '2026-10-10 11:00:00');
    $kept = scheduleFor('Treca', '2026-10-10 12:00:00');

    Livewire::test(ListMediaSchedules::class)
        ->assertCanSeeTableRecords([$first, $second, $kept])
        ->selectTableRecords([$first->getKey(), $second->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(MediaSchedule::pluck('id')->all())->toBe([$kept->id]);
});
