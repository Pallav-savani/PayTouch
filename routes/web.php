<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Wallet view route (only the view, no API logic)
Route::get('/wallet', function () {
    return view('wallet.index');
})->name('wallet.index');

Route::get('/ccbill', function () {
    return view('layouts.frontend.ccbill');
})->name('ccbill');

Route::get('/fastag', function () {
    return view('layouts.frontend.fastag');
})->name('fastag');

// Payment result pages
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/failed', function () {
    return view('payment.failed');
})->name('payment.failed');
