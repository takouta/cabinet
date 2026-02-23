<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientsTableSeeder extends Seeder
{
    public function run()
    {
        // Vider les tables d'abord
        DB::table('patients')->delete();
        DB::table('fournisseurs')->delete();
        DB::table('stock_matiere_premieres')->delete();
        DB::table('rendez_vous')->delete();

        // InsÃ©rer des patients
        DB::table('patients')->insert([
            [
                'id' => 1,
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@email.com',
                'telephone' => '01 23 45 67 89',
                'date_naissance' => '1985-05-15',
                'adresse' => '123 Rue de Paris, 75001 Paris',
                'antecedents_medicaux' => 'Allergie Ã  la pÃ©nicilline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'sophie.martin@email.com',
                'telephone' => '06 12 34 56 78',
                'date_naissance' => '1990-08-22',
                'adresse' => '456 Avenue des Champs, 75008 Paris',
                'antecedents_medicaux' => 'Aucun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nom' => 'Bernard',
                'prenom' => 'Pierre',
                'email' => 'pierre.bernard@email.com',
                'telephone' => '07 89 12 34 56',
                'date_naissance' => '1978-12-03',
                'adresse' => '789 Boulevard Saint-Michel, 75005 Paris',
                'antecedents_medicaux' => 'DiabÃ©tique',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // InsÃ©rer des fournisseurs
        DB::table('fournisseurs')->insert([
            [
                'id' => 1,
                'nom' => 'Dental Supplies',
                'email' => 'contact@dentalsupplies.com',
                'telephone' => '01 45 67 89 10',
                'adresse' => '789 Rue du Commerce, 75002 Paris',
                'specialite' => 'MÃ©dicaments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nom' => 'MediCorp',
                'email' => 'info@medicorp.com',
                'telephone' => '01 56 78 90 12',
                'adresse' => '321 Boulevard Saint-Germain, 75006 Paris',
                'specialite' => 'Consommables',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nom' => 'SafetyPlus',
                'email' => 'contact@safetyplus.com',
                'telephone' => '01 34 56 78 90',
                'adresse' => '654 Avenue de la RÃ©publique, 75011 Paris',
                'specialite' => 'Ã‰quipements de protection',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // InsÃ©rer des stocks
        DB::table('stock_matiere_premieres')->insert([
            [
                'id' => 1,
                'nom' => 'AnesthÃ©sique Local',
                'categorie' => 'MÃ©dicaments',
                'quantite' => 5,
                'seuil_alerte' => 10,
                'unite_mesure' => 'flacons',
                'fournisseur_id' => 1,
                'prix_unitaire' => 15.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nom' => 'Seringues',
                'categorie' => 'Consommables',
                'quantite' => 12,
                'seuil_alerte' => 20,
                'unite_mesure' => 'unitÃ©s',
                'fournisseur_id' => 2,
                'prix_unitaire' => 0.80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nom' => 'Gants StÃ©riles',
                'categorie' => 'Protection',
                'quantite' => 150,
                'seuil_alerte' => 50,
                'unite_mesure' => 'paires',
                'fournisseur_id' => 3,
                'prix_unitaire' => 0.25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nom' => 'Composites Dentaires',
                'categorie' => 'MatÃ©riaux',
                'quantite' => 8,
                'seuil_alerte' => 5,
                'unite_mesure' => 'kits',
                'fournisseur_id' => 1,
                'prix_unitaire' => 45.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // InsÃ©rer des rendez-vous
        DB::table('rendez_vous')->insert([
            [
                'id' => 1,
                'patient_id' => 1,
                'dentiste_id' => 1,
                'date_heure' => '2024-01-27 09:00:00',
                'type_consultation' => 'ContrÃ´le',
                'statut' => 'confirmÃ©',
                'notes' => 'Patient rÃ©gulier, contrÃ´le annuel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'patient_id' => 2,
                'dentiste_id' => 1,
                'date_heure' => '2024-01-27 10:30:00',
                'type_consultation' => 'DÃ©tartrage',
                'statut' => 'planifiÃ©',
                'notes' => 'PremiÃ¨re visite',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'patient_id' => 3,
                'dentiste_id' => 1,
                'date_heure' => '2024-01-28 14:00:00',
                'type_consultation' => 'Soin carie',
                'statut' => 'confirmÃ©',
                'notes' => 'Carie sur molaire infÃ©rieure droite',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // InsÃ©rer des SMS
        DB::table('sms')->insert([
            [
                'id' => 1,
                'numero_destinataire' => '01 23 45 67 89',
                'message' => 'Rappel: Votre rendez-vous est prÃ©vu demain Ã  09:00',
                'type' => 'rappel_rdv',
                'statut' => 'envoyÃ©',
                'envoye_a' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'numero_destinataire' => '01 45 67 89 10',
                'message' => 'Alerte stock: AnesthÃ©sique Local en quantitÃ© critique (5 unitÃ©s)',
                'type' => 'alerte_stock',
                'statut' => 'en_attente',
                'envoye_a' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
