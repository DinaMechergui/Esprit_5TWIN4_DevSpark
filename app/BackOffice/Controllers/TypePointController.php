<?php

namespace App\BackOffice\Controllers;

use App\BackOffice\Requests\StoreTypePointRequest;
use App\BackOffice\Requests\UpdateTypePointRequest;
use App\Http\Controllers\Controller;
use App\Models\TypePoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Gestion des types de points de fraîcheur (back office).
 */
class TypePointController extends Controller
{
    /**
     * Liste paginée des types de points (+ recherche facultative).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $typesPoint = TypePoint::query()
            // Recherche sur le nom ou la description.
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->withCount('points')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('back.types-point.index', [
            'typesPoint' => $typesPoint,
            'search' => $search,
        ]);
    }

    /**
     * Formulaire de création d'un type de point.
     */
    public function create(): View
    {
        return view('back.types-point.create', [
            'typePoint' => new TypePoint(),
        ]);
    }

    /**
     * Enregistrement d'un nouveau type de point.
     */
    public function store(StoreTypePointRequest $request): RedirectResponse
    {
        TypePoint::create($request->validated());

        return redirect()
            ->route('admin.types-point.index')
            ->with('success', 'Le type de point a été créé avec succès.');
    }

    /**
     * Détail d'un type de point (avec ses points rattachés).
     */
    public function show(TypePoint $typePoint): View
    {
        return view('back.types-point.show', [
            'typePoint' => $typePoint,
            'points' => $typePoint->points()->latest()->get(),
        ]);
    }

    /**
     * Formulaire de modification d'un type de point.
     */
    public function edit(TypePoint $typePoint): View
    {
        return view('back.types-point.edit', [
            'typePoint' => $typePoint,
        ]);
    }

    /**
     * Mise à jour d'un type de point.
     */
    public function update(UpdateTypePointRequest $request, TypePoint $typePoint): RedirectResponse
    {
        $typePoint->update($request->validated());

        return redirect()
            ->route('admin.types-point.index')
            ->with('success', 'Le type de point a été modifié avec succès.');
    }

    /**
     * Suppression d'un type de point (refusée s'il a encore des points).
     */
    public function destroy(TypePoint $typePoint): RedirectResponse
    {
        if ($typePoint->points()->exists()) {
            return redirect()
                ->route('admin.types-point.index')
                ->with('error', 'Impossible de supprimer ce type : des points de fraîcheur y sont encore rattachés.');
        }

        $typePoint->delete();

        return redirect()
            ->route('admin.types-point.index')
            ->with('success', 'Le type de point a été supprimé avec succès.');
    }
}
