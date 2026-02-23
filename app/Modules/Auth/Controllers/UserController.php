<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('gestion-users.index', compact('users'));
    }

    public function create()
    {
        return view('gestion-users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin_cabinet,medecin,secretaire,patient,fournisseur,admin,dentiste,assistant',
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|regex:/^[0-9]+$/|min:8|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('gestion-users.index')->with('success', 'Utilisateur cree avec succes!');
    }

    public function edit(User $user)
    {
        return view('gestion-users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin_cabinet,medecin,secretaire,patient,fournisseur,admin,dentiste,assistant',
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|regex:/^[0-9]+$/|min:8|max:20',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('gestion-users.index')->with('success', 'Utilisateur modifie avec succes!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('gestion-users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        $user->delete();

        return redirect()->route('gestion-users.index')->with('success', 'Utilisateur supprime avec succes!');
    }
}
