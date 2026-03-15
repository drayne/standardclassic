<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', IndexController::class);
Route::get('gdje-se-fura-nekultura', StaticController::class)->name('gdje-se-fura-nekultura');
Route::get('zasto-postojimo', StaticController::class)->name('zasto-postojimo');
Route::get('program-radija', StaticController::class)->name('program-radija');

require __DIR__.'/auth.php';
