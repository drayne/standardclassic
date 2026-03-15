<?php

use App\Http\Controllers\Api\PlayedTrackController;
use App\Http\Controllers\Api\RadioController;
use App\Http\Controllers\Api\ScheduledMediaController;

Route::prefix('radio')->group(function () {
    Route::get('/current', [RadioController::class, 'getCurrentTrack'])->name('get-current-track');
    Route::get('/next', [RadioController::class, 'getNextTrack'])->name('get-next-track');

    Route::get('/check-schedule', [ScheduledMediaController::class, 'check'])->name('check-scheduled');

    Route::get('/report-played', [PlayedTrackController::class, 'report'])->name('report-played');
});
