<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/welcome', function () {
    return view('welcome');
})->name('home');

Route::get('/dth', function () {
    return view('layouts.frontend.dth');
})->name('dth');

Route::get('/mobile', function () {
    return view('layouts.frontend.mobile_recharge_tab');
})->name('mobile');

Route::get('/loadWallet', function () {
    return view('layouts.frontend.load_wallet_tab');
})->name('loadWallet');

Route::get('/wallet', function () {
    $user = Auth::user(); // Get the authenticated user
    return view('wallet.index', compact('user'));
})->name('wallet');

Route::get('/ccbill', function () {
    return view('layouts.frontend.ccbill');
})->name('ccbill');

Route::get('/fastag', function () {
    return view('layouts.frontend.fastag');
})->name('fastag');

Route::middleware(['auth'])->group(function () {
    // Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/process-payment', [WalletController::class, 'processPayment'])->name('wallet.process-payment');
    Route::post('/wallet/add-money', [WalletController::class, 'addMoney'])->name('wallet.add-money');
});

// MobiKwik callback routes (no auth middleware needed)
Route::post('/mobikwik/callback', [WalletController::class, 'callback'])->name('mobikwik.callback');
Route::post('/mobikwik/payment-callback', [WalletController::class, 'paymentCallback'])->name('mobikwik.payment-callback');
Route::get('/mobikwik/cancel', [WalletController::class, 'cancel'])->name('mobikwik.cancel');

// Payment result pages
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/failed', function () {
    return view('payment.failed');
})->name('payment.failed');

// Webhook route (no CSRF protection needed)
Route::post('/webhook/mobikwik', [WebhookController::class, 'handleMobiKwikWebhook'])
     ->name('webhook.mobikwik');
