<?php

use App\Exports\DemandesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandeClientController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Translator\TaskController;
use App\Models\Demande;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

// login



Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerClient'])->name('client.register');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {


    Route::get('/demande/create', [DemandeController::class, 'create'])->name('demande.create'); //afichage de form
    Route::post('/demande', [DemandeController::class, 'store'])->name('demande.store'); // enregistrer form
    Route::get('/suivi_demande/index', [DemandeController::class, 'index'])->name('suivi_demande.index'); // affiche table de suivi
    Route::delete('/demandes/{id}', [DemandeController::class, 'destroy'])->name('suivi_demande.destroy');

    Route::get('/demandes/{id}/show', [DemandeController::class, 'show'])->name('suivi_demande.show');
    Route::get('/demandes/{id}/edit', [DemandeController::class, 'edit'])->name('suivi_demande.edit');
    Route::put('/demandes/{id}', [DemandeController::class, 'update'])->name('suivi_demande.update');

    Route::delete('/fichiers/supprimer', [DemandeController::class, 'supprimerFichiers'])->name('fichiers.supprimer');

    Route::put('/suivi_demande/{id}/status', [DemandeController::class, 'updateStatus'])->name('suivi_demande.updateStatus');
    Route::get('/suivi_demande/{id}/download/{fichierId}', [DemandeController::class, 'download'])->name('suivi_demande.download');

    // Route::resource('suivi_demande', DemandeController::class);


    Route::post('/suivi_demande/{id}/upload', [DemandeController::class, 'uploadFiles'])->name('suivi_demande.uploadFiles');


    Route::get('/demande_termine', [DemandeController::class, 'index2'])->name('suivi_demande.index2'); // affiche table de suivi

    Route::get('/statistique', [StatistiqueController::class, 'index'])->name('statistique'); // affiche table de suivi


    // Afficher le formulaire
    Route::get('/admin/translators/create', [App\Http\Controllers\Admin\TranslatorController::class, 'create'])->name('admin.translators.create');

    // Enregistrer le traducteur
    Route::post('/admin/translators', [App\Http\Controllers\Admin\TranslatorController::class, 'store'])->name('admin.translators.store');
    Route::get('/translator', [App\Http\Controllers\Admin\TranslatorController::class, 'index'])->name('translator.index');


    Route::post('/admin/demandes/{demande}/affecter', [App\Http\Controllers\TaskController::class, 'affecter'])->name('admin.demandes.affecter');


    Route::get('/client/home', function () {
        return view('client.home');
    })->name('client.home');




    // client create demande
    Route::get('/client/demande/create', [DemandeClientController::class, 'createClient'])->name('demande.create_client');

    Route::get('/client/mes-demandes', [DemandeClientController::class, 'mesDemandes'])->name('client.mes_demandes');

    Route::post('/client/demande/store', [DemandeClientController::class, 'storeFromClient'])->name('demande.store_client');


    // imprimer
    Route::get('/demande/imprimer_pdf', [DemandeController::class, 'imprimerPDF'])->name('demande.imprimer_pdf');
    Route::post('/demande/{id}/confirmer-reception', [DemandeClientController::class, 'confirmerReception'])
        ->name('demande.confirmer_reception');

    Route::get('/admin/demandes/confirmées', [DemandeController::class, 'demandesConfirmees'])->name('admin.demandes.confirmees');
    Route::post('/admin/demande/{id}/envoyer-message', [DemandeController::class, 'envoyerMessage'])->name('admin.demande.envoyer_message');
    Route::post('/admin/demande/{id}/envoyer-message', [DemandeController::class, 'envoyerMessage'])->name('admin.demande.envoyer_message');


    Route::get('/client/messages', [DemandeClientController::class, 'messages'])->name('client.messages');



});


// Page tableau de bord
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/home', function () {
    return view('home'); // une autre page si besoin
})->name('home');


Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profil.edit');
Route::put('/profil/update', [ProfileController::class, 'update'])->name('profil.update');

// µµµµµµµµµµµµµµµµµµµµµµµµµµµµµ


Route::get('/lang/{locale}', function ($locale) {
    $availableLocales = ['en', 'fr', 'ar'];  // langues acceptées
    if (in_array($locale, $availableLocales)) {
        session(['locale' => $locale]);  // on enregistre la langue en session
    }
    return redirect()->back();
})->name('lang.switch');
Route::get('/export-demandes', function (Request $request) {
    $mois = $request->query('mois'); // Exemple: 2025-07

    if ($mois) {
        $year = substr($mois, 0, 4);
        $month = substr($mois, 5, 2);

        $demandes = Demande::whereYear('date_debut', $year)
            ->whereMonth('date_debut', $month)
            ->get();
    } else {
        $demandes = Demande::all();
    }

    return Excel::download(new DemandesExport($demandes), 'demandes_' . $mois . '.xlsx');
})->name('export.demandes');
