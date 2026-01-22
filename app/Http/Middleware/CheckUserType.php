<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Vérifie si l'utilisateur connecté a le bon type
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $type)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur a le champ type_user
        if (!isset($user->type_user)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Type de compte invalide.');
        }

        // Vérifier le type d'utilisateur
        $expectedType = strtoupper($type);
        $actualType = strtoupper($user->type_user);

        if ($actualType !== $expectedType) {
            // Rediriger vers le bon dashboard selon le type
            if ($actualType === 'GROSSISTE') {
                return redirect()->route('grossiste.dashboard')
                    ->with('error', 'Vous n\'avez pas accès à cet espace.');
            } elseif ($actualType === 'PARTENAIRE') {
                return redirect()->route('partenaire.dashboard')
                    ->with('error', 'Vous n\'avez pas accès à cet espace.');
            }

            abort(403, 'Accès non autorisé pour ce type de compte.');
        }

        return $next($request);
    }
}
