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


});

    // Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    // Route::post('/wallet/create', [WalletController::class, 'createWallet'])->name('wallet.create');
    // Route::post('/wallet/add-money', [WalletController::class, 'addMoney'])->name('wallet.add-money');
    // Route::post('/wallet/transfer', [WalletController::class, 'transferMoney'])->name('wallet.transfer');
    // Route::get('/wallet/balance', [WalletController::class, 'getBalance'])->name('wallet.balance');
    // Route::get('/wallet/transactions', [WalletController::class, 'getTransactions'])->name('wallet.transactions');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/wallet', [APIWalletController::class, 'index'])->name('wallet');
//     Route::post('/wallet/add-money', [APIWalletController::class, 'addMoney'])->name('wallet.add-money');
//     Route::post('/wallet/transfer', [APIWalletController::class, 'transferMoney'])->name('wallet.transfer');
//     Route::post('/wallet/bank-account', [APIWalletController::class, 'addBankAccount'])->name('wallet.add-bank-account');
//     Route::get('/wallet/transactions', [APIWalletController::class, 'getTransactions'])->name('wallet.transactions');
//     Route::post('/wallet/sync-balance', [APIWalletController::class, 'syncBalance'])->name('wallet.sync-balance');
//     Route::get('/wallet/bank-accounts', [APIWalletController::class, 'getBankAccounts'])->name('wallet.bank-accounts');
//     Route::delete('/wallet/bank-account/{id}', [APIWalletController::class, 'deleteBankAccount'])->name('wallet.delete-bank-account');
// });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('wallet')->group(function () {
        Route::get('balance', [WalletApiController::class, 'getBalance']);
        Route::post('payment-breakdown', [WalletApiController::class, 'getPaymentBreakdown']);
        Route::post('process-payment', [WalletApiController::class, 'processPayment'])
             ->middleware('validate.payment');
        Route::get('transactions', [WalletApiController::class, 'getTransactionHistory']);
        Route::get('transaction-status', [WalletApiController::class, 'getTransactionStatus']);
    });
});

// Add these routes to your existing routes/api.php file

// CC Bill Payment Routes
Route::prefix('cc-bill-payments')->group(function () {
    // Public routes (with authentication middleware)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [CcBillPaymentController::class, 'index']);
        Route::post('/', [CcBillPaymentController::class, 'store']);
        Route::get('/{id}', [CcBillPaymentController::class, 'show']);
        Route::get('/user/{userId}', [CcBillPaymentController::class, 'getUserPayments']);
        Route::post('/{id}/retry', [CcBillPaymentController::class, 'retryPayment']);
        
        // Status-based routes
        Route::get('/status/pending', [CcBillPaymentController::class, 'getPendingPayments']);
        Route::get('/status/failed', [CcBillPaymentController::class, 'getFailedPayments']);
        Route::get('/status/successful', [CcBillPaymentController::class, 'getSuccessfulPayments']);
        
        // Statistics
        Route::get('/stats/dashboard', [CcBillPaymentController::class, 'getStatistics']);
    });
    
    // Admin only routes
    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::put('/{id}', [CcBillPaymentController::class, 'update']);
        Route::patch('/{id}', [CcBillPaymentController::class, 'update']);
        Route::delete('/{id}', [CcBillPaymentController::class, 'destroy']);
    });
});

Route::post('/recharge/submit', [MobileRechargeController::class, 'submit'])->name('recharge.submit');
Route::get('/recharge/history', [MobileRechargeController::class, 'history'])->name('recharge.history');


// DTH Recharge Routes
Route::prefix('dth')->group(function () {
    // Get all DTH recharges (with optional filtering and pagination)
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

// Search History Routes
Route::prefix('search-history')->group(function () {
    Route::post('/', [SearchHistoryController::class, 'store']);
    Route::get('/', [SearchHistoryController::class, 'index']);
    Route::get('/statistics', [SearchHistoryController::class, 'statistics']);
    Route::get('/most-searched', [SearchHistoryController::class, 'mostSearched']);
    Route::delete('/cleanup', [SearchHistoryController::class, 'cleanup']);
});


