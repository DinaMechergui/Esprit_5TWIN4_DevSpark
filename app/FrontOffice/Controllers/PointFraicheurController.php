<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PointFraicheur;
use App\Models\TypePoint;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pages publiques du module « Points de fraîcheur » (front office).
 */
class PointFraicheurController extends Controller
{
    /**
     * Liste des points de fraîcheur (filtre par type + recherche, 9 par page).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $type = $request->query('type');

        $points = PointFraicheur::query()
            // Chargement du type pour éviter le problème du N+1.
            ->with('type')
            // Filtre sur le type de point.
            ->when($type, function ($query, $type) {
                $query->where('type_point_id', $type);
            })
            // Recherche sur le nom ou l'adresse.
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('adresse', 'like', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->paginate(9)
            ->withQueryString();

        $types = TypePoint::withCount('points')
            ->orderBy('nom')
            ->get();

        return view('front.points-fraicheur.index', [
            'points' => $points,
            'types' => $types,
            'search' => $search,
            'selectedType' => $types->firstWhere('id', $type),
        ]);
    }

    /**
     * Détail public d'un point de fraîcheur.
     */
    public function show(PointFraicheur $pointFraicheur): View
    {
        return view('front.points-fraicheur.show', [
            'point' => $pointFraicheur->load('type'),
        ]);
    }
}
