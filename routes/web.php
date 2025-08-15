<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/requests/auction', [RequestsController::class, 'getRequests'])
        ->name('requests.auction');
    Route::get('/requests/review', [RequestsController::class, 'getReview'])
        ->name('requests.review');
    Route::get('/requests/complete', [RequestsController::class, 'complete'])
        ->name('requests.complete');
    Route::get('/requests/{id}/get-data', [RequestsController::class, 'getData'])->name('requests.get-data');
    Route::post('/requests/{id}/create-auction', [RequestsController::class, 'create'])->name('requests.create-auction');
    Route::get('/auctions/{id}', [AuctionController::class, 'show'])->name('auctions.show');
    Route::post('/add-request', [RequestsController::class, 'addRequest'])
        ->name('addRequest');
    Route::post('/requests/{request}/status', [RequestsController::class, 'updateStatus'])->name('requests.updateStatus');
    Route::post('/requests/{request}/end', [RequestsController::class, 'updateStatusEnd'])->name('requests.updateStatusEnd');
    Route::post('/calculate-price', [RequestsController::class, 'calculate'])
        ->name('calculatePrice');
    Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('upload.avatar');
    Route::post('/save-avatar', [ProfileController::class, 'saveAvatar'])->name('save.avatar');
    Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/{auction}', [AuctionController::class, 'showAuction'])->name('auctions.showAuction');
    Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->name('bids.store');
    Route::post('/auctions/{auction}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/auctions/{auction}/bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/auctions/{auction}/comments', [CommentController::class, 'index'])->name('comments.index');
    // Если у вас есть другие защищенные маршруты, добавьте их здесь
});

// Маршруты аутентификации Breeze (если используете полный функционал)
require __DIR__.'/auth.php';
