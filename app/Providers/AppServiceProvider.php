<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Carbon::setLocale('sr_Latn');
        $translator = Carbon::getTranslator();
        if (method_exists($translator, 'setMessages')) {
            $translator->setMessages('sr_Latn', [
                'ago' => 'prije :time',
                'before' => 'prije :time',
                'diff_before_yesterday' => 'prekjuče',
            ]);
        }
    }
}
