<?php

use Illuminate\Support\Facades\Route;




// Page tableau de bord
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/home', function () {
    return view('home'); // une autre page si besoin
})->name('home');
