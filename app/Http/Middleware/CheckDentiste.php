<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDentiste
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur est admin_cabinet ou medecin
        if ($user->role === 'admin_cabinet' || $user->role === 'medecin') {
            return $next($request);
        }

        // Si l'utilisateur n'est pas autorisé
        return redirect()->route('dashboard')
                        ->with('error', 'Accès non autorisé. Cette section est réservée aux médecins et administrateurs.');
    }
}
