<?php

namespace App\BackOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use App\Models\CategorieConseil;
use App\Models\Conseil;
class DashboardController extends Controller
{
    /**
     * Tableau de bord du back office (statistiques simples).
     */
    public function index(): View
    {
        $stats = [
            // Nombre total d'utilisateurs inscrits.
            'users' => User::count(),
            // Nombre d'administrateurs.
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
            // Nouveaux utilisateurs sur les 30 derniers jours.
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            // Utilisateurs ayant vérifié leur adresse e-mail.
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
        ];

        // Derniers inscrits affichés sur le tableau de bord.
        $latestUsers = User::latest()->take(5)->get();
        // Module « Conseils » : statistiques et dernier conseil publié.
        $conseilStats = [
            'categories' => CategorieConseil::count(),
            'conseils' => Conseil::count(),
            'derniere_categorie' => CategorieConseil::withCount('conseils')
                ->orderByDesc('conseils_count')
                ->first(),
        ];
        return view('back.pages.dashboard', [
            'stats' => $stats,
            'latestUsers' => $latestUsers,
             'conseilStats' => $conseilStats,
        ]);
    }
}
