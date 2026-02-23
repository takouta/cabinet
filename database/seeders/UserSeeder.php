<?php

namespace Database\Seeders;

use App\Modules\Auth\Models\User;
use App\Modules\Fournisseur\Models\Fournisseur;
use App\Modules\Patient\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['nom' => 'Admin', 'prenom' => 'Super', 'name' => 'Super Admin', 'email' => 'super.admin@cabinet.com', 'role' => 'super_admin', 'telephone' => '0123456789', 'adresse' => '123 Rue Administrative'],
            ['nom' => 'Martin', 'prenom' => 'Sophie', 'name' => 'Sophie Martin', 'email' => 'admin@cabinet.com', 'role' => 'admin_cabinet', 'telephone' => '0123456788', 'adresse' => '45 Avenue du Cabinet'],
            ['nom' => 'Dubois', 'prenom' => 'Jean', 'name' => 'Dr Jean Dubois', 'email' => 'dr.dubois@cabinet.com', 'role' => 'medecin', 'telephone' => '0123456787', 'adresse' => '12 Rue des Dentistes'],
            ['nom' => 'Petit', 'prenom' => 'Marie', 'name' => 'Dr Marie Petit', 'email' => 'dr.petit@cabinet.com', 'role' => 'medecin', 'telephone' => '0123456786', 'adresse' => '8 Boulevard des Soins'],
            ['nom' => 'Bernard', 'prenom' => 'Julie', 'name' => 'Julie Bernard', 'email' => 'secretaire@cabinet.com', 'role' => 'secretaire', 'telephone' => '0123456785', 'adresse' => '3 Rue de l\'Accueil'],
            ['nom' => 'Durand', 'prenom' => 'Pierre', 'name' => 'Pierre Durand', 'email' => 'pierre.durand@email.com', 'role' => 'patient', 'telephone' => '0612345678', 'adresse' => '15 Rue des Patients'],
            ['nom' => 'Leroy', 'prenom' => 'Sophie', 'name' => 'Sophie Leroy', 'email' => 'sophie.leroy@email.com', 'role' => 'patient', 'telephone' => '0687654321', 'adresse' => '25 Avenue des Patients'],
            ['nom' => 'DentalPro', 'prenom' => 'Services', 'name' => 'DentalPro Services', 'email' => 'contact@dentalpro.com', 'role' => 'fournisseur', 'telephone' => '0143256789', 'adresse' => '55 Rue du Commerce'],
            ['nom' => 'MediDent', 'prenom' => 'Distribution', 'name' => 'MediDent Distribution', 'email' => 'contact@medident.fr', 'role' => 'fournisseur', 'telephone' => '0478654321', 'adresse' => '12 Zone Industrielle'],
        ];

        foreach ($users as $data) {
            $payload = [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
                'role' => $this->resolveStorableRole($data['role']),
            ];

            if (Schema::hasColumn('users', 'nom')) {
                $payload['nom'] = $data['nom'];
            }

            if (Schema::hasColumn('users', 'prenom')) {
                $payload['prenom'] = $data['prenom'];
            }

            if (Schema::hasColumn('users', 'actif')) {
                $payload['actif'] = true;
            }

            User::updateOrCreate(
                ['email' => $data['email']],
                $payload
            );
        }

        Patient::updateOrCreate(
            ['email' => 'pierre.durand@email.com'],
            [
                'nom' => 'Durand',
                'prenom' => 'Pierre',
                'telephone' => '0612345678',
                'date_naissance' => '1985-06-15',
                'adresse' => '15 Rue des Patients',
            ]
        );

        Patient::updateOrCreate(
            ['email' => 'sophie.leroy@email.com'],
            [
                'nom' => 'Leroy',
                'prenom' => 'Sophie',
                'telephone' => '0687654321',
                'date_naissance' => '1990-03-22',
                'adresse' => '25 Avenue des Patients',
            ]
        );

        Fournisseur::updateOrCreate(
            ['email' => 'contact@dentalpro.com'],
            [
                'nom' => 'DentalPro SARL',
                'telephone' => '0143256790',
                'adresse' => '55 Rue du Commerce, Paris',
                'specialite' => 'Materiel dentaire',
            ]
        );

        Fournisseur::updateOrCreate(
            ['email' => 'contact@medident.fr'],
            [
                'nom' => 'MediDent Distribution',
                'telephone' => '0478654322',
                'adresse' => '12 Zone Industrielle, Lyon',
                'specialite' => 'Consommables dentaires',
            ]
        );
    }

    private function resolveStorableRole(string $requestedRole): string
    {
        $column = DB::selectOne("SHOW COLUMNS FROM users WHERE Field = 'role'");
        $type = strtolower($column->Type ?? '');

        if (str_contains($type, 'enum')) {
            if (str_contains($type, $requestedRole)) {
                return $requestedRole;
            }

            return match ($requestedRole) {
                'super_admin', 'admin_cabinet' => 'admin',
                'medecin' => 'dentiste',
                'secretaire', 'patient', 'fournisseur' => 'assistant',
                default => 'assistant',
            };
        }

        return $requestedRole;
    }
}
