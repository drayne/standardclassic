<?php

namespace App\Traits;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            // Ako je artikal, sačekajmo da se postavi slug/preveden naslov
            if (method_exists($model, 'getDisplayName') && empty($model->getDisplayName())) {
                return;
            }
            $model->logActivity('Kreirano: ' . $model->getDisplayName());
        });

        static::updated(function (Model $model) {
            $dirty = $model->getDirty();
            $keys = array_keys($dirty);
            $original = $model->getOriginal();

            // Ako je artikal, osigurajmo da su relacije osvežene kako bi getDisplayName() vratio naslov ako je dodat
            if (method_exists($model, 'translations')) {
                $model->refresh();
            }

            // Proveravamo da li su jedine promene slug ili image i da li su prethodno bili prazni (indikacija post-create update-a)
            $isPostCreateSlug = in_array('slug', $keys) && (empty($original['slug']) || $original['slug'] === '');
            $isPostCreateImage = in_array('image', $keys) && empty($original['image']);

            $displayName = $model->getDisplayName();

            if (count($dirty) > 0 && count($dirty) <= 2 && ($isPostCreateSlug || $isPostCreateImage)) {
                // Provera da li je već logovano "Kreiran" za ovaj model u ovoj sesiji/requestu
                // Kako bismo izbegli dupliranje ako je created već uspeo da loguje
                $alreadyLogged = \App\Models\ActivityLog::where('subject_id', $model->getKey())
                    ->where('subject_type', $model->getMorphClass())
                    ->where('description', 'like', 'Kreira%')
                    ->where('created_at', '>=', now()->subSeconds(2))
                    ->exists();

                if (!$alreadyLogged) {
                    $model->logActivity('Kreirano: ' . $displayName);
                }
                return;
            }

            $model->logActivity('Ažurirano: ' . $displayName);
        });

        static::deleted(function (Model $model) {
            $model->logActivity('Obrisano: ' . $model->getDisplayName());
        });
    }

    public function logActivity(string $description, array $properties = []): void
    {
        app(ActivityLogService::class)->log($description, $this, $properties);
    }

    public function getDisplayName()
    {
        // Ako je u pitanju Article, pokušaj dohvatiti naslov iz baze direktno da zaobiđeš keširane relacije
        if ($this instanceof \App\Models\Article || method_exists($this, 'translations')) {
            $translation = \App\Models\ArticleTranslation::where('article_id', $this->getKey())
                ->whereHas('language', function ($query) {
                    $query->where('code', 'sr');
                })->first();

            if ($translation && !empty($translation->title)) {
                return $translation->title;
            }
        }

        return $this->name ?? $this->title ?? (string) $this->getKey();
    }
}
