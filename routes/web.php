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


Route::get('/demande/create', [DemandeController::class, 'create'])->name('demande.create'); //afichage de form
Route::post('/demande', [DemandeController::class, 'store'])->name('demande.store'); // enregistrer form
Route::get('/suivi_demande/index', [DemandeController::class, 'index'])->name('suivi_demande.index'); // affiche table de suivi

Route::get('/demandes/{id}/show', [DemandeController::class, 'show'])->name('suivi_demande.show');
Route::get('/demandes/{id}/edit', [DemandeController::class, 'edit'])->name('suivi_demande.edit');
Route::put('/demandes/{id}', [DemandeController::class, 'update'])->name('suivi_demande.update');

Route::delete('/fichiers/supprimer', [DemandeController::class, 'supprimerFichiers'])->name('fichiers.supprimer');

Route::put('/suivi_demande/{id}/status', [DemandeController::class, 'updateStatus'])->name('suivi_demande.updateStatus');
Route::get('/suivi_demande/{id}/download/{fichierId}', [DemandeController::class, 'download'])->name('suivi_demande.download');

Route::resource('suivi_demande', DemandeController::class);


Route::post('/suivi_demande/{id}/upload', [DemandeController::class, 'uploadFiles'])->name('suivi_demande.uploadFiles');


Route::get('/demande_termine', [DemandeController::class, 'index2'])->name('suivi_demande.index2'); // affiche table de suivi

