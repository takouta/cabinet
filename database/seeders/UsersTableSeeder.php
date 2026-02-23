<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CrÃƒÂ©er l'utilisateur admin/dentiste principal
        User::create([
            'name' => 'Dr. Sophie Martin',
            'email' => 'admin@cabinet-dentaire.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'specialite' => 'Dentiste gÃƒÂ©nÃƒÂ©raliste',
            'telephone' => '+33123456789'
        ]);

        // CrÃƒÂ©er un dentiste supplÃƒÂ©mentaire
        User::create([
            'name' => 'Dr. Pierre Dubois',
            'email' => 'dentiste@cabinet-dentaire.com',
            'password' => Hash::make('password123'),
            'role' => 'dentiste',
            'specialite' => 'Orthodontie',
            'telephone' => '+33123456780'
        ]);

        // CrÃƒÂ©er un assistant
        User::create([
            'name' => 'Marie Assistant',
            'email' => 'assistant@cabinet-dentaire.com',
            'password' => Hash::make('password123'),
            'role' => 'assistant',
            'telephone' => '+33123456781'
        ]);

        $this->command->info('3 utilisateurs crÃƒÂ©ÃƒÂ©s avec succÃƒÂ¨s!');
        $this->command->info('Email: admin@cabinet-dentaire.com | Mot de passe: password123');
        $this->command->info('Email: dentiste@cabinet-dentaire.com | Mot de passe: password123');
        $this->command->info('Email: assistant@cabinet-dentaire.com | Mot de passe: password123');
    }
}

