<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getColumns(): int|array
    {
        return 4;
    }
}
