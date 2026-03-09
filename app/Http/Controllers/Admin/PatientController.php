<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    /**
     * Afficher la liste des patients
     */
    public function index(Request $request)
    {
        $query = Patient::with('user');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Enregistrer un nouveau patient
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make('password123'),
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'role' => 'patient',
            'actif' => true,
        ]);

        // Créer le patient
        $patient = Patient::create([
            'user_id' => $user->id,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance ?? '',
            'sexe' => $request->sexe ?? 'M',
            'numero_securite_sociale' => $request->numero_securite_sociale ?? '',
            'mutuelle' => $request->mutuelle,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route($this->getRoutePrefix() . 'patients.index')
            ->with('success', 'Patient créé avec succès');
    }

    /**
     * Afficher les détails d'un patient
     */
    public function show($id)
    {
        $patient = Patient::with(['user', 'rendezVous' => function($q) {
            $q->latest()->limit(5);
        }])->findOrFail($id);

        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Mettre à jour un patient
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
        ]);

        // Mettre à jour l'utilisateur
        $patient->user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        // Mettre à jour le patient
        $patient->update([
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'sexe' => $request->sexe,
            'numero_securite_sociale' => $request->numero_securite_sociale,
            'mutuelle' => $request->mutuelle,
        ]);

        return redirect()->route($this->getRoutePrefix() . 'patients.index')
            ->with('success', 'Patient mis à jour avec succès');
    }

    /**
     * Supprimer un patient
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $userId = $patient->user_id;
        
        // Supprimer le patient (la cascade supprimera l'utilisateur si configuré)
        $patient->delete();
        
        // Ou supprimer aussi l'utilisateur
        User::where('id', $userId)->delete();

        return redirect()->route($this->getRoutePrefix() . 'patients.index')
            ->with('success', 'Patient supprimé avec succès');
    }

    /**
     * Recherche de patients (AJAX)
     */
    public function search(Request $request)
    {
        $term = $request->get('q');
        
        $patients = Patient::whereHas('user', function($q) use ($term) {
            $q->where('nom', 'like', "%{$term}%")
              ->orWhere('prenom', 'like', "%{$term}%");
        })->with('user')->limit(10)->get();
        
        return response()->json($patients);
    }

    /**
     * Activer/Désactiver un patient
     */
    public function toggleStatus($id)
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;
        
        $user->actif = !$user->actif;
        $user->save();

        return response()->json([
            'success' => true,
            'actif' => $user->actif
        ]);
    }

    protected function getRoutePrefix()
    {
        $routeName = optional(request()->route())->getName() ?? '';
        return str_contains($routeName, 'admin') ? 'admin.' : '';
    }
}