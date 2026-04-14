<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(string $description, ?Model $subject = null, array $properties = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => $description,
            'subject_id' => $subject?->getKey(),
            'subject_type' => $subject?->getMorphClass(),
            'properties' => $properties,
        ]);
    }
}
