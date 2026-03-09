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
        $userRole = trim($user->role);
        
        // Handle cases where roles might be passed as a single comma-separated string
        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }
        $allowedRoles = array_map('trim', $allowedRoles);

        if (!in_array($userRole, $allowedRoles, true)) {
            $roleToRoute = [
                'super_admin' => 'super_admin.dashboard',
                'admin_cabinet' => 'admin.dashboard',
                'medecin' => 'medecin.dashboard',
                'secretaire' => 'secretaire.dashboard',
                'patient' => 'patient.dashboard',
                'fournisseur' => 'fournisseur.dashboard',
            ];

            if (!isset($roleToRoute[$user->role])) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Votre rôle n\'est pas reconnu. Veuillez contacter l\'administrateur.');
            }

            return redirect()->route($roleToRoute[$user->role])
                ->with('error', 'Vous n\'avez pas accès à cette page.');
        }

        return $next($request);
    }
}
