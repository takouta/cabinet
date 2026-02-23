<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un utilisateur patient s'il n'existe pas
        $user = User::firstOrCreate(
            ['email' => 'patient@demo.com'],
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'actif' => true,
            ]
        );

        // Créer le patient associé
        Patient::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'date_naissance' => '1985-05-15',
                'lieu_naissance' => 'Paris',
                'sexe' => 'M',
                'numero_securite_sociale' => '185057512345678',
                'mutuelle' => 'MGEN',
                'adresse' => '15 Rue des Patients',
                'telephone' => '0612345678',
            ]
        );

        $this->command->info('Patient de démonstration créé avec succès!');
    }
}
