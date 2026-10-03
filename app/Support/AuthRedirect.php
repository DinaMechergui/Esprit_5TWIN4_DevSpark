<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

/**
 * Centralise les redirections post-authentification.
 */
class AuthRedirect
{
    /**
     * URL de destination après une action liée à l'authentification.
     *
     * Les administrateurs sont dirigés vers le back office, les autres
     * vers le front office. Une URL d'origine n'est conservée que si elle
     * est compatible avec le rôle de l'utilisateur.
     */
    public static function url(string $suffix = ''): string
    {
        $user = Auth::user();

        $default = $user?->isAdmin()
            ? route('admin.dashboard', absolute: false)
            : route('front.home', absolute: false);

        $intended = session()->pull('url.intended');

        if (filled($intended)) {
            // Une page /admin ne reste utilisable que pour un administrateur.
            if (! str_contains($intended, '/admin') || $user?->isAdmin()) {
                return $intended.$suffix;
            }
        }

        return $default.$suffix;
    }
}
