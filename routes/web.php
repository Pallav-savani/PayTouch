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

Route::get('/DTH', function () {
    return view('layouts.frontend.dth');
})->name('dth');

Route::get('/mobile recharges', function () {
    return view('layouts.frontend.mobile_recharge_tab');
})->name('mobile');

// Wallet view route (only the view, no API logic)
Route::get('/Load Wallet', function () {
    return view('layouts.frontend.wallet');
})->name('wallet');

Route::get('/CCBill', function () {
    return view('layouts.frontend.ccbill');
})->name('ccbill');

Route::get('/Fastag recharges', function () {
    return view('layouts.frontend.fastag');
})->name('fastag');

// Payment result pages
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/failed', function () {
    return view('payment.failed');
})->name('payment.failed');

Route::get('/my_account', function () {
    return view('layouts.frontend.my_account');
})->name('myaccount');

Route::get('/utility_bills', function () {
    return view('layouts.frontend.utility_bills');
})->name('utilitybills');
