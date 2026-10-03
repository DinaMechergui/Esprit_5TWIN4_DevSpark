<?php

/*
|--------------------------------------------------------------------------
| Back office — réservé aux administrateurs
|--------------------------------------------------------------------------
|
| Préfixe : /admin — protégé par les middleware « auth » + « admin ».
|
| Note : les routes du back office sont préfixées par « admin. » et ne
| sont jamais utilisées par le front office.
|
*/

use App\BackOffice\Controllers\DashboardController;
use App\BackOffice\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Tableau de bord : /admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs (tâche commune à l'équipe).
    Route::resource('users', UserController::class);

    /*
    | Module « Points de fraîcheur » (CRUD à créer par l'équipe) :
    |
    | Route::resource('points-fraicheur', PointFraicheurController::class)
    |     ->except(['show'])
    |     ->parameters(['points-fraicheur' => 'pointFraicheur']);
    */
});
