<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;

// Публичная главная страница
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/bots', [DashboardController::class, 'storeBot'])->name('dashboard.bots.store');

    // Управление ботами
    Route::prefix('bots')->name('bots.')->group(function () {
        Route::get('/{bot}', [BotController::class, 'show'])->name('show');
        Route::delete('/{bot}/subscribers/{subscriber}', [BotController::class, 'destroySubscriber'])->name('subscribers.destroy');
        Route::post('/{bot}/broadcast', [BotController::class, 'broadcast'])->name('broadcast');
    });
});

require __DIR__.'/auth.php';
