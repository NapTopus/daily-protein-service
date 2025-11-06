<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/api/documentation');

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('auth.login');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('throttle:refresh-token')->name('auth.refresh');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('throttle:writes')->group(function () {
        Route::patch('/users/me', [UserController::class, 'update'])->name('users.update');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('records.destroy');
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    });

    Route::middleware('throttle:api')->group(function () {
        Route::get('/users/me', [UserController::class, 'show'])->name('users.show');
        Route::get('/records', [RecordController::class, 'index'])->name('records.show');
    });
});
