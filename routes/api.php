<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/api/documentation');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/item', [ItemController::class, 'store']);
    Route::patch('/item/{item}', [ItemController::class, 'update']);
    Route::delete('/item/{item}', [ItemController::class, 'destroy']);
    Route::get('/record', [RecordController::class, 'showByDate']);
    Route::delete('/record/{record}', [RecordController::class, 'destroy']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
