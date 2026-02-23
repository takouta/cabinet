<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles, true)) {
            $roleToRoute = [
                'super_admin' => 'super_admin.dashboard',
                'admin_cabinet' => 'admin.dashboard',
                'medecin' => 'medecin.dashboard',
                'secretaire' => 'secretaire.dashboard',
                'patient' => 'patient.dashboard',
                'fournisseur' => 'fournisseur.dashboard',
                // Compatibilite avec les roles deja presents
                'admin' => 'admin.dashboard',
                'dentiste' => 'medecin.dashboard',
                'assistant' => 'patient.dashboard',
            ];

            $defaultRoute = $roleToRoute[$user->role] ?? 'dashboard';

            return redirect()->route($defaultRoute)
                ->with('error', 'Vous n\'avez pas acces a cette page.');
        }

        return $next($request);
    }
}
