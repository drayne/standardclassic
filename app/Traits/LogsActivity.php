<?php

namespace App\Traits;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            $model->logActivity('Kreiran ' . $model->getDisplayName());
        });

        static::updated(function (Model $model) {
            $model->logActivity('Ažuriran ' . $model->getDisplayName());
        });

        static::deleted(function (Model $model) {
            $model->logActivity('Obrisan ' . $model->getDisplayName());
        });
    }

    public function logActivity(string $description, array $properties = []): void
    {
        app(ActivityLogService::class)->log($description, $this, $properties);
    }

    public function getDisplayName(): string
    {
        return $this->name ?? $this->title ?? (string) $this->getKey();
    }
}
