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
