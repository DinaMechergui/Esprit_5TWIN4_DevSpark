<?php

namespace App\FrontOffice\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\FrontOffice\Requests\Auth\LoginRequest;
use App\Support\AuthRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('front.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirection après connexion : les administrateurs vont au back office,
        // les utilisateurs simples retournent sur le front office ; une éventuelle
        // URL d'origine n'est utilisée que si elle est compatible avec le rôle.
        return redirect()
            ->to(AuthRedirect::url())
            ->with('success', 'Connexion réussie.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('front.home')->with('success', 'Vous êtes déconnecté.');
    }
}
