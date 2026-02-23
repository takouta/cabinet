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
        // VÃ©rifier si l'utilisateur est connectÃ©
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = Auth::user();
        
        // VÃ©rifier si l'utilisateur est admin ou dentiste
        if ($user->role === 'admin' || $user->role === 'dentiste') {
            return $next($request);
        }

        // Si l'utilisateur n'est pas autorisÃ©
        return redirect()->route('dashboard')
                        ->with('error', 'AccÃ¨s non autorisÃ©. Cette section est rÃ©servÃ©e aux dentistes et administrateurs.');
    }
}
