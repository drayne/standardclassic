<?php

use App\Http\Controllers\Api\RadioController;
use App\Http\Controllers\Api\ScheduledMediaController;

Route::get('/radio/next', [RadioController::class, 'getNextTrack'])->name('get-next-track');

Route::get('/radio/check-schedule', [ScheduledMediaController::class, 'check'])->name('check-scheduled');
