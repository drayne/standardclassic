<?php

use App\Http\Controllers\Api\RadioController;

Route::get('/radio/next', [RadioController::class, 'getNextTrack'])->name('get-next-track');
