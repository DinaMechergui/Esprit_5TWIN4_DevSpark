<?php

namespace App\BackOffice\Controllers;

use App\BackOffice\Requests\StoreConseilRequest;
use App\BackOffice\Requests\UpdateConseilRequest;
use App\Http\Controllers\Controller;
use App\Models\CategorieConseil;
use App\Models\Conseil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConseilController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $conseils = Conseil::with('categorie')
            ->recherche($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('back.conseils.index', compact('conseils', 'search'));
    }

    public function create(): View
    {
        return view('back.conseils.create', [
            'conseil' => new Conseil(),
            'categories' => CategorieConseil::orderBy('nom')->get(),
        ]);
    }

    public function store(StoreConseilRequest $request): RedirectResponse
    {
        Conseil::create($request->validated());

        return redirect()->route('admin.conseils.index')
            ->with('success', 'Le conseil a été créé avec succès.');
    }

    public function show(Conseil $conseil): View
    {
        return view('back.conseils.show', compact('conseil'));
    }

    public function edit(Conseil $conseil): View
    {
        return view('back.conseils.edit', [
            'conseil' => $conseil,
            'categories' => CategorieConseil::orderBy('nom')->get(),
        ]);
    }

    public function update(UpdateConseilRequest $request, Conseil $conseil): RedirectResponse
    {
        $conseil->update($request->validated());

        return redirect()->route('admin.conseils.index')
            ->with('success', 'Le conseil a été modifié avec succès.');
    }

    public function destroy(Conseil $conseil): RedirectResponse
    {
        $conseil->delete();

        return redirect()->route('admin.conseils.index')
            ->with('success', 'Le conseil a été supprimé avec succès.');
    }
}
