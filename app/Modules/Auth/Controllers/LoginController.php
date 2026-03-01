<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Afficher le formulaire de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gerer la tentative de connexion.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Ces identifiants ne correspondent pas a nos enregistrements.'],
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (isset($user->actif) && $user->actif === false) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['Votre compte est desactive. Contactez l\'administrateur.'],
            ]);
        }

        if (array_key_exists('derniere_connexion', $user->getAttributes())
            || in_array('derniere_connexion', $user->getFillable(), true)) {
            $user->forceFill(['derniere_connexion' => now()])->save();
        }

        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Rediriger l'utilisateur vers son dashboard selon son role.
     */
    protected function redirectBasedOnRole(?string $role)
    {
        $roleToRoute = [
            'super_admin' => 'super_admin.dashboard',
            'admin_cabinet' => 'admin.dashboard',
            'medecin' => 'medecin.dashboard',
            'secretaire' => 'secretaire.dashboard',
            'patient' => 'patient.dashboard',
            'fournisseur' => 'fournisseur.dashboard',
        ];

        $route = $roleToRoute[$role ?? ''] ?? 'dashboard';

        return redirect()->route($route);
    }

    /**
     * Deconnexion.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * API login pour les applications mobiles.
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        $user = Auth::user();

        if (isset($user->actif) && $user->actif === false) {
            Auth::logout();

            return response()->json([
                'message' => 'Compte desactive',
            ], 403);
        }

        if (array_key_exists('derniere_connexion', $user->getAttributes())
            || in_array('derniere_connexion', $user->getFillable(), true)) {
            $user->forceFill(['derniere_connexion' => now()])->save();
        }

        $token = method_exists($user, 'createToken')
            ? $user->createToken('auth_token')->plainTextToken
            : null;

        return response()->json([
            'message' => 'Connexion reussie',
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom ?? $user->name,
                'prenom' => $user->prenom ?? null,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
            'redirect' => $this->getDashboardPath($user->role),
        ]);
    }

    /**
     * Deconnexion API.
     */
    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Deconnexion reussie',
        ]);
    }

    protected function getDashboardPath(?string $role): string
    {
        $paths = [
            'super_admin' => '/super-admin/dashboard',
            'admin_cabinet' => '/admin/dashboard',
            'medecin' => '/medecin/dashboard',
            'secretaire' => '/secretaire/dashboard',
            'patient' => '/patient/dashboard',
            'fournisseur' => '/fournisseur/dashboard',
        ];

        return $paths[$role ?? ''] ?? '/dashboard';
    }
}
