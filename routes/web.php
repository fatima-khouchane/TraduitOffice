<?php

use App\Http\Controllers\DemandeController;
use Illuminate\Support\Facades\Route;




// Page tableau de bord
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/home', function () {
    return view('home'); // une autre page si besoin
})->name('home');


Route::get('/demande/create', [DemandeController::class, 'create'])->name('demande.create');
Route::post('/demande', [DemandeController::class, 'store'])->name('demande.store');
