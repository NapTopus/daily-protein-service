<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/api/documentation');

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('throttle:writes')->group(function () {
        Route::patch('/users/{user}/defaultTarget', [UserController::class, 'updateDefaultTarget'])->name('users.updateDefaultTarget');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('records.destroy');
    });

    Route::middleware('throttle:api')->group(function () {
        Route::get('/users/me', [UserController::class, 'show'])->name('users.show');
        Route::get('/records', [RecordController::class, 'index'])->name('records.show');
    });
});
