<?php

namespace App\BackOffice\Controllers;

use App\Http\Controllers\Controller;
use App\BackOffice\Requests\StoreUserRequest;
use App\BackOffice\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Gestion des utilisateurs du back office (tâche commune à l'équipe).
 */
class UserController extends Controller
{
    /**
     * Liste paginée des utilisateurs (+ recherche facultative).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            // Recherche sur le nom ou l'adresse e-mail.
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('back.pages.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * Formulaire de création d'un utilisateur.
     */
    public function create(): View
    {
        return view('back.pages.users.create');
    }

    /**
     * Enregistrement d'un nouvel utilisateur.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été créé avec succès.');
    }

    /**
     * Détail d'un utilisateur.
     */
    public function show(User $user): View
    {
        return view('back.pages.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Formulaire de modification d'un utilisateur.
     */
    public function edit(User $user): View
    {
        return view('back.pages.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Mise à jour d'un utilisateur.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Mot de passe facultatif : on ne le met à jour que s'il est renseigné.
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été modifié avec succès.');
    }

    /**
     * Suppression d'un utilisateur (un administrateur ne peut pas se
     * supprimer lui-même).
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }
}
