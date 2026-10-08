<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use App\Models\TypeSignalement;
use App\BackOffice\Requests\StoreSignalementRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Pages publiques du module « Signalements » (front office).
 */
class SignalementController extends Controller
{
    /**
     * Liste paginée des signalements publics.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $typeId = $request->query('type');

        $signalements = Signalement::query()
            ->with(['typeSignalement', 'user'])
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->when($typeId, function ($query, $typeId) {
                $query->where('type_signalement_id', $typeId);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $types = TypeSignalement::orderBy('libelle')->get();

        return view('front.signalements.index', [
            'signalements' => $signalements,
            'types'        => $types,
            'search'       => $search,
            'selectedType' => $typeId,
            'statuts'      => Signalement::STATUTS,
        ]);
    }

    /**
     * Détail public d'un signalement.
     */
    public function show(Signalement $signalement): View
    {
        return view('front.signalements.show', [
            'signalement' => $signalement->load(['typeSignalement', 'user']),
            'statuts'     => Signalement::STATUTS,
        ]);
    }

    /**
     * Formulaire de création d'un signalement (utilisateur connecté).
     */
    public function create(): View
    {
        return view('front.signalements.create', [
            'signalement'      => new Signalement(),
            'typesSignalement' => TypeSignalement::orderBy('libelle')->get(),
            'statuts'          => Signalement::STATUTS,
        ]);
    }

    /**
     * Enregistrement d'un signalement depuis le front office.
     * Le user_id est rempli avec l'utilisateur connecté.
     */
    public function store(StoreSignalementRequest $request, \App\Services\SignalementPriorityService $priorityService): RedirectResponse
    {
        $priorite = $priorityService->determinePriority($request->description);

        Signalement::create(array_merge($request->validated(), [
            'user_id'  => Auth::id(),
            'statut'   => Signalement::STATUT_NOUVEAU,
            'priorite' => $priorite,
        ]));

        return redirect()
            ->route('signalements.index')
            ->with('success', 'Votre signalement a été envoyé avec succès.');
    }
}
