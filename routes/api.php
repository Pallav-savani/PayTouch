<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DthController;
use App\Http\Controllers\API\RechargeSuccessController;
use App\Http\Controllers\API\RechargePendingController;
use App\Http\Controllers\API\RechargeFailController;
use App\Http\Controllers\Api\SearchHistoryController;
use App\Http\Controllers\API\RechargeApiSuccessController;
use App\Http\Controllers\API\RechargeApiPendingController;
use App\Http\Controllers\API\RechargeApiFailController;
use App\Http\Controllers\API\WalletController as APIWalletController;
use App\Http\Controllers\API\CcBillPaymentController;
use App\Http\Controllers\API\MobileRechargeController;
use App\Http\Controllers\Api\WalletApiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('users', UserController::class);
    Route::apiResource('rechargesuccess', RechargeSuccessController::class);    
    Route::apiResource('rechargefail', RechargeFailController::class); 
    Route::apiResource('rechargepending', RechargePendingController::class); 

    Route::apiResource('rechargeAPIsuccess', RechargeApiSuccessController::class);
    Route::apiResource('rechargeAPIpending', RechargeApiPendingController::class); 
    Route::apiResource('rechargeAPIfail', RechargeApiFailController::class); 

    // Wallet API Routes
    Route::prefix('wallet')->group(function () {
        Route::get('/', [APIWalletController::class, 'index']);
        Route::get('/user-data', [APIWalletController::class, 'getUserData']);
        Route::get('/transactions', [APIWalletController::class, 'getTransactions']);
        Route::post('/add-money', [APIWalletController::class, 'addMoney']);
        Route::post('/process-payment', [APIWalletController::class, 'processPayment']);
        Route::get('/balance', [APIWalletController::class, 'getBalance']);
        Route::get('/wallet/details', [APIWalletController::class, 'getWalletDetails']);
    });

    // DTH Recharge Routes
    Route::prefix('dth')->group(function () {
        Route::get('/', [DthController::class, 'index']);
        Route::post('/', [DthController::class, 'store']);
        Route::get('/{id}', [DthController::class, 'show']);
        Route::put('/{id}', [DthController::class, 'update']);
        Route::patch('/{id}', [DthController::class, 'update']);
        Route::delete('/{id}', [DthController::class, 'destroy']);
        Route::get('/pending', [DthController::class, 'getPendingTransactions']);
        Route::post('/{id}/retry', [DthController::class, 'retryTransaction']);
        Route::post('/retry-all', [DthController::class, 'retryAllPending']);
        Route::get('/failed', [DthController::class, 'getFailedTransactions']);
        Route::post('/{id}/retry-failed', [DthController::class, 'retryFailedTransaction']);
        Route::post('/retry-all-failed', [DthController::class, 'retryAllFailed']);
        Route::get('/stats/dashboard', [DthController::class, 'statistics']);
    });

    Route::post('/recharge/submit', [MobileRechargeController::class, 'submit']);
    Route::get('/recharge/history', [MobileRechargeController::class, 'history']);
    Route::get('/recharge/search', [MobileRechargeController::class, 'search']);
    Route::get('/recharge/statistics', [MobileRechargeController::class, 'statistics']);

});

// Callback routes (no auth needed)
Route::prefix('wallet')->group(function () {
    Route::post('/callback', [APIWalletController::class, 'callback']);
    Route::post('/payment-callback', [APIWalletController::class, 'paymentCallback']);
    Route::get('/cancel', [APIWalletController::class, 'cancel']);
});

// CC Bill Payment Routes
Route::prefix('cc-bill-payments')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [CcBillPaymentController::class, 'index']);
        Route::post('/', [CcBillPaymentController::class, 'store']);
        Route::get('/{id}', [CcBillPaymentController::class, 'show']);
        Route::get('/user/{userId}', [CcBillPaymentController::class, 'getUserPayments']);
        Route::post('/{id}/retry', [CcBillPaymentController::class, 'retryPayment']);
        
        Route::get('/status/pending', [CcBillPaymentController::class, 'getPendingPayments']);
        Route::get('/status/failed', [CcBillPaymentController::class, 'getFailedPayments']);
        Route::get('/status/successful', [CcBillPaymentController::class, 'getSuccessfulPayments']);
        
        Route::get('/stats/dashboard', [CcBillPaymentController::class, 'getStatistics']);
    });
    
    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::put('/{id}', [CcBillPaymentController::class, 'update']);
        Route::patch('/{id}', [CcBillPaymentController::class, 'update']);
        Route::delete('/{id}', [CcBillPaymentController::class, 'destroy']);
    });
});


// Search History Routes
Route::prefix('search-history')->group(function () {
    Route::post('/', [SearchHistoryController::class, 'store']);
    Route::get('/', [SearchHistoryController::class, 'index']);
    Route::get('/statistics', [SearchHistoryController::class, 'statistics']);
    Route::get('/most-searched', [SearchHistoryController::class, 'mostSearched']);
    Route::delete('/cleanup', [SearchHistoryController::class, 'cleanup']);
});
