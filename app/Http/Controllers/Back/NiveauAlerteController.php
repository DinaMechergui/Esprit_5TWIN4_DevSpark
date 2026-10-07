<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\StoreNiveauAlerteRequest;
use App\Http\Requests\Back\UpdateNiveauAlerteRequest;
use App\Models\NiveauAlerte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NiveauAlerteController extends Controller
{
    /**
     * Liste paginée des niveaux d'alerte avec recherche facultative.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $niveauxAlerte = NiveauAlerte::query()
            ->when($search, function ($query, $search) {
                $query->where('libelle', 'like', "%{$search}%")
                    ->orWhere('couleur', 'like', "%{$search}%");
            })
            ->withCount('alertes')
            ->orderBy('niveau', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('back.niveaux-alerte.index', [
            'niveauxAlerte' => $niveauxAlerte,
            'search' => $search,
        ]);
    }

    /**
     * Formulaire de création d'un niveau d'alerte.
     */
    public function create(): View
    {
        return view('back.niveaux-alerte.create', [
            'niveauAlerte' => new NiveauAlerte(),
        ]);
    }

    /**
     * Enregistrement d'un nouveau niveau d'alerte.
     */
    public function store(StoreNiveauAlerteRequest $request): RedirectResponse
    {
        NiveauAlerte::create($request->validated());

        return redirect()
            ->route('admin.niveaux-alerte.index')
            ->with('success', 'Le niveau d\'alerte a été créé avec succès.');
    }

    /**
     * Affichage détaillé d'un niveau d'alerte.
     */
    public function show(NiveauAlerte $niveauAlerte): View
    {
        $alertes = $niveauAlerte->alertes()->latest('date_debut')->paginate(10);

        return view('back.niveaux-alerte.show', [
            'niveauAlerte' => $niveauAlerte,
            'alertes' => $alertes,
        ]);
    }

    /**
     * Formulaire d'édition d'un niveau d'alerte.
     */
    public function edit(NiveauAlerte $niveauAlerte): View
    {
        return view('back.niveaux-alerte.edit', [
            'niveauAlerte' => $niveauAlerte,
        ]);
    }

    /**
     * Mise à jour d'un niveau d'alerte.
     */
    public function update(UpdateNiveauAlerteRequest $request, NiveauAlerte $niveauAlerte): RedirectResponse
    {
        $niveauAlerte->update($request->validated());

        return redirect()
            ->route('admin.niveaux-alerte.index')
            ->with('success', 'Le niveau d\'alerte a été modifié avec succès.');
    }

    /**
     * Suppression d'un niveau d'alerte (refusée si des alertes y sont associées).
     */
    public function destroy(NiveauAlerte $niveauAlerte): RedirectResponse
    {
        if ($niveauAlerte->alertes()->exists()) {
            return redirect()
                ->route('admin.niveaux-alerte.index')
                ->with('error', 'Impossible de supprimer ce niveau d\'alerte : des alertes y sont encore rattachées.');
        }

        $niveauAlerte->delete();

        return redirect()
            ->route('admin.niveaux-alerte.index')
            ->with('success', 'Le niveau d\'alerte a été supprimé avec succès.');
    }
}
