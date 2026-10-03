<?php

/*
|--------------------------------------------------------------------------
| Front office — site public et espace utilisateur
|--------------------------------------------------------------------------
|
| Toutes les routes accessibles aux visiteurs (et à l'utilisateur connecté)
| sont déclarées ici : page d'accueil, profil, module public à venir, ainsi
| que l'authentification (fichier auth.php).
|
*/

use App\FrontOffice\Controllers\HomeController;
use App\FrontOffice\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil.
Route::get('/', [HomeController::class, 'index'])->name('front.home');

/*
| Module « Points de fraîcheur » (à créer par l'équipe) :
| les routes du module seront ajoutées ici, puis commentées seront retirées.
|
| Route::get('/points-fraicheur', [PointFraicheurController::class, 'index'])
|     ->name('points-fraicheur.index');
| Route::get('/points-fraicheur/{pointFraicheur}', [PointFraicheurController::class, 'show'])
|     ->scopeBindings()
|     ->name('points-fraicheur.show');
*/

// Profil de l'utilisateur connecté.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentification : connexion, inscription, mots de passe, vérification d'e-mail.
require __DIR__.'/auth.php';
