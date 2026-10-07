<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategorieConseil;
use App\Models\Conseil;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConseilController extends Controller
{
    // Liste publique : filtre par catégorie + recherche, 9 par page.
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $conseils = Conseil::with('categorie')
            ->when($request->query('categorie'), fn ($q, $id) => $q->where('categorie_conseil_id', $id))
            ->recherche($search)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = CategorieConseil::withCount('conseils')->orderBy('nom')->get();

        return view('front.conseils.index', [
            'conseils' => $conseils,
            'categories' => $categories,
            'search' => $search,
            'selectedCategorie' => $categories->firstWhere('id', $request->query('categorie')),
        ]);
    }

    // Page détail d'un conseil.
    public function show(Conseil $conseil): View
    {
        return view('front.conseils.show', [
            'conseil' => $conseil->load('categorie'),
        ]);
    }
}
