<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use App\Modules\Patient\Models\Patient;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/patient/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showPatientRegistrationForm()
    {
        $cabinets = \App\Models\Cabinet::all();
        $medecins = \App\Modules\Auth\Models\User::whereIn('role', ['dentiste', 'medecin'])->get();
        return view('auth.register', ['patientMode' => true, 'cabinets' => $cabinets, 'medecins' => $medecins]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:patient,fournisseur'],
        ]);
    }

    protected function create(array $data)
    {
        $role = $data['role'] ?? 'patient';
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role,  // Role inputé, sinon patient par defaut
            'actif'    => false,      // Attente d'activation
        ]);

        if ($role === 'fournisseur' && Schema::hasTable('fournisseurs')) {
            \App\Modules\Fournisseur\Models\Fournisseur::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom' => $data['name'],
                    'telephone' => 'A renseigner',
                    'adresse' => 'A renseigner',
                    'specialite' => 'Général',
                ]
            );
        }

        return $user;
    }

    public function registerPatient(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telephone' => ['required', 'regex:/^[0-9]+$/', 'min:8', 'max:20'],
            'date_naissance' => ['required', 'date'],
            'adresse' => ['required', 'string'],
            'cabinet_id' => ['nullable', 'exists:cabinets,id'],
            'medecin_id' => ['nullable', 'exists:users,id'],
        ];

        $validated = $request->validate($rules);

        $storableRole = $this->resolvePatientRoleForCurrentSchema();

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $storableRole,
        ];

        if (Schema::hasColumn('users', 'actif')) {
            $payload['actif'] = false; // Désactivé par défaut pour les patients
        }

        $user = User::create($payload);

        if (Schema::hasTable('patients')) {
            [$prenom, $nom] = $this->splitName($validated['name']);

            Patient::create([
                'user_id' => $user->id, // Lien crucial manquant
                'email' => $validated['email'],
                'nom' => $nom ?: $validated['name'],
                'prenom' => $prenom,
                'telephone' => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'adresse' => $validated['adresse'],
                'cabinet_id' => $validated['cabinet_id'] ?? null,
                'medecin_id' => $validated['medecin_id'] ?? null,
            ]);
        }

        // Suppression de la connexion automatique
        // auth()->login($user);
        // $request->session()->regenerate();

        return redirect()->route('login')->with('success', 'Compte patient créé. Veuillez attendre l\'activation par l\'administrateur.');
    }

    private function resolvePatientRoleForCurrentSchema(): string
    {
        if (!Schema::hasColumn('users', 'role')) {
            return 'patient';
        }

        $column = \DB::selectOne("SHOW COLUMNS FROM users WHERE Field = 'role'");
        $type = strtolower($column->Type ?? '');

        // Si c'est un enum, on vérifie si 'patient' est dedans
        if (str_contains($type, 'enum')) {
            if (str_contains($type, 'patient')) {
                return 'patient';
            }
            return 'assistant';
        }

        // Si c'est un varchar ou text, on peut mettre n'importe quelle valeur
        return 'patient';
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $prenom = $parts[0] ?? '';
        $nom = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        return [$prenom, $nom];
    }
}
