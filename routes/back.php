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
use App\BackOffice\Controllers\PointFraicheurController;
use App\BackOffice\Controllers\TypePointController;
use App\BackOffice\Controllers\UserController;
use App\Http\Controllers\Back\AlerteMeteoController as BackAlerteMeteoController;
use App\Http\Controllers\Back\NiveauAlerteController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Tableau de bord : /admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs (tâche commune à l'équipe).
    Route::resource('users', UserController::class);

    // Module « Points de fraîcheur » : les types de points (parent).
    Route::resource('types-point', TypePointController::class)
        ->parameters(['types-point' => 'typePoint']);

    // Module « Points de fraîcheur » : les points (enfant).
    Route::resource('points-fraicheur', PointFraicheurController::class)
        ->parameters(['points-fraicheur' => 'pointFraicheur']);

    // Module « Alertes météo » : les niveaux d'alerte (parent).
    Route::resource('niveaux-alerte', NiveauAlerteController::class)
        ->parameters(['niveaux-alerte' => 'niveau_alerte']);

    // Module « Alertes météo » : les alertes météo (enfant).
    Route::resource('alertes-meteo', BackAlerteMeteoController::class)
        ->parameters(['alertes-meteo' => 'alerte_meteo']);
});

