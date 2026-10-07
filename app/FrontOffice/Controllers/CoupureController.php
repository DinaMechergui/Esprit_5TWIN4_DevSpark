<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupure;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoupureController extends Controller
{
    public function index(Request $request): View
    {
        $quartierId = $request->query('quartier');
        $coupures = Coupure::query()
            ->with('quartier')
            ->whereIn('statut', ['en_cours', 'prevue'])
            ->when($quartierId, fn ($query, $quartierId) => $query->where('quartier_id', $quartierId))
            ->orderByRaw("CASE WHEN statut = 'en_cours' THEN 0 ELSE 1 END")
            ->orderBy('date_debut')
            ->paginate(9)
            ->withQueryString();

        return view('front.coupures.index', [
            'coupures' => $coupures,
            'quartiers' => Quartier::orderBy('nom')->get(),
            'selectedQuartier' => $quartierId,
        ]);
    }

    public function show(Coupure $coupure): View
    {
        abort_unless(in_array($coupure->statut, ['en_cours', 'prevue'], true), 404);

        return view('front.coupures.show', ['coupure' => $coupure->load('quartier')]);
    }
}
