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

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showPatientRegistrationForm()
    {
        return view('auth.register', ['patientMode' => true]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
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
            $payload['actif'] = true;
        }

        $user = User::create($payload);

        if (Schema::hasTable('patients')) {
            [$prenom, $nom] = $this->splitName($validated['name']);

            Patient::create([
                'email' => $validated['email'],
                'nom' => $nom ?: $validated['name'],
                'prenom' => $prenom,
                'telephone' => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'adresse' => $validated['adresse'],
            ]);
        }

        auth()->login($user);
        $request->session()->regenerate();

        // Si la base ne supporte pas encore "patient", le fallback "assistant"
        // est redirige vers le dashboard patient.
        if ($storableRole !== 'patient') {
            return redirect()->route('patient.dashboard')->with(
                'success',
                'Compte cree. Role patient non disponible sur ce schema, migration requise.'
            );
        }

        return redirect()->route('patient.dashboard')->with('success', 'Compte patient cree avec succes.');
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
