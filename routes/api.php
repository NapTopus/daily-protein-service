<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/api/documentation');

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware(['auth:sanctum', 'throttle:writes'])->group(function () {
    Route::post('/item', [ItemController::class, 'store']);
    Route::patch('/item/{item}', [ItemController::class, 'update']);
    Route::delete('/item/{item}', [ItemController::class, 'destroy']);
    Route::delete('/record/{record}', [RecordController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/record', [RecordController::class, 'showByDate']);
});
