<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RemoteController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Публичные маршруты (доступны без аутентификации)
Route::get('/', [RemoteController::class, 'showFirstPage'])->name('dashboard');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login/auth', [LoginController::class, 'auth'])->name('auth');
Route::get('/login/registration', [RegistrationController::class, 'view'])->name('registration');
Route::post('/login/registration/set', [RegistrationController::class, 'setRegistration'])->name('setRegistration');

// Защищенные маршруты (требуют аутентификации)
Route::middleware(['auth'])->group(function () {
    Route::post('/addRequest', [RequestsController::class, 'addRequest'])->name('addRequest');
    Route::post('/calculate', [RequestsController::class, 'calculate'])->name('calculatePrice');

    // Если у вас есть другие защищенные маршруты, добавьте их здесь
});

// Маршруты аутентификации Breeze (если используете полный функционал)
require __DIR__.'/auth.php';
