<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\SeatController;
use App\Http\Middleware\AdminMiddleware;

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// Venue Routes
Route::group(['prefix' => 'venues'], function () {
    Route::get('/', [VenueController::class, 'index']);
    Route::post('/', [VenueController::class, 'store'])->middleware(AdminMiddleware::class);
    Route::put('/{id}', [VenueController::class, 'update'])->middleware(AdminMiddleware::class);
    Route::delete('/{id}', [VenueController::class, 'destroy'])->middleware(AdminMiddleware::class);
});

// Event Routes
Route::group(['prefix' => 'events'], function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::post('/', [EventController::class, 'store'])->middleware(AdminMiddleware::class);
    Route::put('/{id}', [EventController::class, 'update'])->middleware(AdminMiddleware::class);
    Route::delete('/{id}', [EventController::class, 'destroy'])->middleware(AdminMiddleware::class);
});

// Seat Routes
Route::group(['prefix' => 'seats'], function () {
    Route::get('/', [SeatController::class, 'index']); // Tüm koltukları listele
    Route::post('/', [SeatController::class, 'store'])->middleware(AdminMiddleware::class); // Koltuk ekle (Admin Only)
    Route::get('/{id}', [SeatController::class, 'show']); // Tek bir koltuk getir
    Route::put('/{id}', [SeatController::class, 'update'])->middleware(AdminMiddleware::class); // Koltuğu güncelle (Admin Only)
    Route::delete('/{id}', [SeatController::class, 'destroy'])->middleware(AdminMiddleware::class); // Koltuğu sil (Admin Only)
    Route::post('/block', [SeatController::class, 'blockSeats']);
    Route::post('/release', [SeatController::class, 'releaseSeats']);
});
Route::get('/events/{id}/seats', [SeatController::class, 'getSeatsByEvent']);
Route::get('/venues/{id}/seats', [SeatController::class, 'getSeatsByVenue']);
