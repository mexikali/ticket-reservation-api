<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\AdminMiddleware;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::group(['prefix' => 'events'], function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::post('/', [EventController::class, 'store'])->middleware(AdminMiddleware::class);
    Route::put('/{id}', [EventController::class, 'update'])->middleware(AdminMiddleware::class);
    Route::delete('/{id}', [EventController::class, 'destroy'])->middleware(AdminMiddleware::class);
});

