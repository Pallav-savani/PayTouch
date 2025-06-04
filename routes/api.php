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

// DTH Recharge Routes
Route::prefix('dth')->group(function () {
    // Get all DTH recharges (with optional filtering and pagination)
    Route::get('/', [DthController::class, 'index']);
    Route::post('/', [DthController::class, 'store']);
    Route::get('/{id}', [DthController::class, 'show']);
    Route::put('/{id}', [DthController::class, 'update']);
    Route::patch('/{id}', [DthController::class, 'update']);
    Route::delete('/{id}', [DthController::class, 'destroy']);
    Route::get('/stats/dashboard', [DthController::class, 'statistics']);
});