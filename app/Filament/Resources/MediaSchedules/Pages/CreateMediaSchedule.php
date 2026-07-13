<?php

namespace App\Filament\Resources\MediaSchedules\Pages;

use App\Filament\Resources\MediaSchedules\MediaScheduleResource;
use App\Models\MediaSchedule;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMediaSchedule extends CreateRecord
{
    protected static string $resource = MediaScheduleResource::class;

    public function getTitle(): string
    {
        return 'Nova zakazana emisija';
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $mediaId = $data['media_id'];
        $scheduledTimes = $data['scheduled_times'] ?? [];

        $firstRecord = null;

        foreach ($scheduledTimes as $timeData) {
            $record = MediaSchedule::create([
                'media_id' => $mediaId,
                'scheduled_at' => $timeData['scheduled_at'],
                'played' => false,
            ]);

            if (!$firstRecord) {
                $firstRecord = $record;
            }
        }

        return $firstRecord;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
