<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirige vers la page de connexion
        }

        // Récupère le rôle de l'utilisateur authentifié
        $userRole = Auth::user()->role;

        // Vérifie si le rôle de l'utilisateur est dans les rôles autorisés
        if (!in_array($userRole, $roles)) {
            
            abort(403, 'Unauthorized to see this page'); // Renvoie une erreur 403 si non autorisé
        }

        return $next($request); // Poursuit la requête si autorisé
    }
}
