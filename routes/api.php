<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/api/documentation');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/item', [ItemController::class, 'store']);
    Route::patch('/item/{id}', [ItemController::class, 'update']);
    Route::delete('/item/{id}', [ItemController::class, 'destroy']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
