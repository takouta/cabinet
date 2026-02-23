<?php
// app/Http/Controllers/Patient/DashboardController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient; // Correction: App\Models\Patient au lieu de App\Modules\Patient\Models\Patient
use App\Models\RendezVous; // Correction: App\Models\RendezVous
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord patient
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer le patient via user_id (relation correcte)
        $patient = Patient::where('user_id', $user->id)->first();
        
        // Option 1: Rediriger si pas de patient associé
        if (!$patient) {
            return view('patient.dashboard', [
                'patient' => null,
                'prochains_rdv' => collect(),
                'stats' => [
                    'total_rdv' => 0,
                    'dernier_rdv' => null,
                ],
                'message' => 'Votre profil patient n\'est pas encore configuré. Veuillez contacter l\'administration.'
            ]);
        }
        
        // Option 2: Création automatique (si vous préférez)
        // if (!$patient) {
        //     $patient = $this->createPatientFromUser($user);
        // }

        // Récupérer les prochains rendez-vous
        $prochains_rdv = RendezVous::with(['medecin' => function($query) {
                $query->with('user'); // Charger les infos du médecin
            }])
            ->where('patient_id', $patient->id)
            ->where('date_heure', '>=', now())
            ->whereIn('statut', ['planifie', 'confirme'])
            ->orderBy('date_heure')
            ->limit(5)
            ->get();

        // Statistiques
        $stats = [
            'total_rdv' => RendezVous::where('patient_id', $patient->id)->count(),
            'total_factures' => $patient->factures()->count() ?? 0,
            'montant_total' => $patient->factures()->sum('montant_total') ?? 0,
            'dernier_rdv' => RendezVous::where('patient_id', $patient->id)
                ->where('date_heure', '<', now())
                ->with('medecin')
                ->latest('date_heure')
                ->first(),
        ];

        // Journalisation pour débogage
        Log::info('Dashboard patient chargé', [
            'user_id' => $user->id,
            'patient_id' => $patient->id,
            'rdv_count' => $prochains_rdv->count()
        ]);

        return view('patient.dashboard', compact('patient', 'prochains_rdv', 'stats'));
    }

    /**
     * Créer un patient à partir d'un utilisateur (optionnel)
     */
    private function createPatientFromUser($user)
    {
        try {
            $patient = Patient::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'date_naissance' => now()->subYears(30), // Valeur par défaut
                'lieu_naissance' => 'Non renseigné',
                'sexe' => 'M', // Valeur par défaut
                'numero_securite_sociale' => 'N/A',
                'adresse' => $user->adresse ?? 'Non renseignée',
                'telephone' => $user->telephone ?? '0000000000',
            ]);

            Log::info('Patient créé automatiquement', [
                'user_id' => $user->id,
                'patient_id' => $patient->id
            ]);

            return $patient;

        } catch (\Exception $e) {
            Log::error('Erreur création patient', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Méthode utilitaire pour splitName (si nécessaire)
     */
    private function splitName($user): array
    {
        $prenom = trim((string) ($user->prenom ?? ''));
        $nom = trim((string) ($user->nom ?? ''));

        if ($prenom !== '' && $nom !== '') {
            return [$prenom, $nom];
        }

        $fullName = trim((string) ($user->name ?? ''));
        if ($fullName === '') {
            return ['Patient', 'Sans nom'];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];
        $fallbackPrenom = $parts[0] ?? 'Patient';
        $fallbackNom = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'Sans nom';

        return [$prenom ?: $fallbackPrenom, $nom ?: $fallbackNom];
    }
}