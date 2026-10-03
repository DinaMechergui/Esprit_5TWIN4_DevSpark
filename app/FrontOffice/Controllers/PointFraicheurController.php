<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PointFraicheur;
use App\Models\TypePoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
     * Carte interactive des points de fraîcheur (Leaflet + OpenStreetMap).
     */
    public function carte(): View
    {
        // Uniquement les champs utiles au JS, en nombres pour la carte.
        $points = PointFraicheur::query()
            ->with('type')
            ->orderBy('nom')
            ->get()
            ->map(fn (PointFraicheur $point) => [
                'id' => $point->id,
                'nom' => $point->nom,
                'type' => $point->type?->nom,
                'type_id' => $point->type_point_id,
                'adresse' => $point->adresse,
                'lat' => (float) $point->latitude,
                'lng' => (float) $point->longitude,
                'horaires' => $point->horaires,
                'accessible' => (bool) $point->accessible,
                'url' => route('points-fraicheur.show', $point),
            ])
            ->values();

        // Types de points pour le filtre de la carte (sans rechargement).
        $types = TypePoint::withCount('points')
            ->orderBy('nom')
            ->get();

        return view('front.points-fraicheur.carte', [
            'points' => $points,
            'types' => $types,
        ]);
    }

    /**
     * Points les plus proches d'une position, triés par distance (JSON).
     *
     * Requête attendue : /points-fraicheur/proches?lat=..&lng=..[&limit=..]
     * Réponse : 200 avec les points, ou 422 si les coordonnées sont invalides.
     */
    public function proches(Request $request): JsonResponse
    {
        // Validation explicite pour garantir une réponse JSON 422,
        // quelle que soit la valeur de l'en-tête Accept.
        $validator = Validator::make($request->query(), [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Coordonnées de géolocalisation invalides.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lat = (float) $request->query('lat');
        $lng = (float) $request->query('lng');
        // 20 résultats par défaut, 50 au maximum.
        $limit = (int) ($request->query('limit') ?: 20);

        $points = PointFraicheur::query()
            // Chargement du type : pas de problème du N+1.
            ->with('type')
            ->lesPlusProches($lat, $lng)
            ->limit($limit)
            ->get()
            ->map(fn (PointFraicheur $point) => [
                'id' => $point->id,
                'nom' => $point->nom,
                'type' => $point->type?->nom,
                'type_id' => $point->type_point_id,
                'lat' => (float) $point->latitude,
                'lng' => (float) $point->longitude,
                'horaires' => $point->horaires,
                'accessible' => (bool) $point->accessible,
                'url' => route('points-fraicheur.show', $point),
                // Distance en km, arrondie à 1 décimale.
                'distance' => round((float) $point->distance, 1),
            ])
            ->values();

        return response()->json(['points' => $points]);
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
