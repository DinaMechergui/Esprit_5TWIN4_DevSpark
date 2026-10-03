<?php

namespace App\BackOffice\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware "admin" : restreint l'accès au back office aux utilisateurs
 * possédant le rôle "admin". Les autres utilisateurs reçoivent une erreur 403.
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Accès refusé : cette page est réservée aux administrateurs.');
        }

        return $next($request);
    }
}
