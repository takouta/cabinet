<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('statut')) {
            $query->where('actif', $request->statut === 'actif');
        }

        // Sécurité : le super_admin ne voit et n'active que les admins
        $query->whereIn('role', ['admin_cabinet']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy($request->get('sort', 'created_at'), $request->get('order', 'desc'));

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'actifs' => User::where('actif', true)->count(),
            'patients' => User::where('role', 'patient')->count(),
            'medecins' => User::where('role', 'medecin')->count(),
        ];

        return view('super_admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $roles = [
            'admin_cabinet' => 'Admin Cabinet',
        ];

        return view('super_admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|string|in:admin_cabinet',
            'telephone' => 'nullable|regex:/^[0-9]+$/|min:8|max:30',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'name' => trim($validated['prenom'].' '.$validated['nom']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'actif' => true,
        ]);

        return redirect()->route('super_admin.users.show', $user->id)
            ->with('success', 'Utilisateur cree avec succes.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('super_admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = [
            'admin_cabinet' => 'Admin Cabinet',
        ];

        return view('super_admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|string|in:admin_cabinet',
            'telephone' => 'nullable|regex:/^[0-9]+$/|min:8|max:30',
            'adresse' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $payload = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'name' => trim($validated['prenom'].' '.$validated['nom']),
            'email' => $validated['email'],
            'role' => $validated['role'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('super_admin.users.show', $user->id)
            ->with('success', 'Utilisateur mis a jour.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // All roles can be toggled by Super Admin for now
        // (Previously restricted to admin, admin_cabinet)

        $user->actif = !$user->actif;
        $user->save();

        return response()->json([
            'success' => true,
            'actif' => $user->actif,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('super_admin.users.index')
            ->with('success', 'Utilisateur supprime.');
    }
}