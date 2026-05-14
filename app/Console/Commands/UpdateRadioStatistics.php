<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRadioStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radio:update-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pripkuplja statistiku slušalaca sa Icecast servera i ažurira peak za danas.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Http\Services\RadioService $radioService)
    {
        $stat = $radioService->updateStatistics();
        $this->info("Statistika ažurirana. Trenutno: {$stat->current}, Današnji peak: {$stat->highest}");
    }
}
