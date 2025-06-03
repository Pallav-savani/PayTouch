<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('users', UserController::class);
    
});

Route::prefix('dth')->group(function () {
    Route::get('/', [DthController::class, 'index']);
    Route::post('/', [DthController::class, 'store']);
    Route::get('/{id}', [DthController::class, 'show']);
    Route::put('/{id}', [DthController::class, 'update']);
    Route::delete('/{id}', [DthController::class, 'destroy']);
    Route::get('/mobile/{mobile}', [DthController::class, 'getByMobile']);
    Route::get('/service/{service}', [DthController::class, 'getByService']);
    Route::get('/stats/overview', [DthController::class, 'getStats']);
});