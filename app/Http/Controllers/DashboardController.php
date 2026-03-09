<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Patient\Models\Patient;
use App\Modules\RendezVous\Models\RendezVous;
use App\Modules\Stock\Models\StockMatierePremiere;
use App\Modules\Fournisseur\Models\Fournisseur;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques principales
        $stats = [
            'totalPatients' => Patient::count(),
            'rendezVousAujourdhui' => RendezVous::whereDate('date_heure', today())->count(),
            'alertesStock' => StockMatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')->count(),
            'totalFournisseurs' => Fournisseur::count(),
            'rdvEnAttente' => RendezVous::where('statut', 'confirmé')->count(),
            'patientsNouveauxMois' => Patient::whereMonth('created_at', now()->month)->count(),
        ];

        // Données supplémentaires pour les vues détaillées
        $rdvAujourdhuiList = RendezVous::with('patient')
            ->whereDate('date_heure', today())
            ->orderBy('date_heure')
            ->get();

        $stockAlerteList = StockMatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')
            ->orderBy('quantite')
            ->get();

        $fournisseurs = Fournisseur::whereIn('id', [1, 2])->get();

        // Rendez-vous à venir (7 prochains jours)
        $rdvProchains = RendezVous::with('patient')
            ->whereDate('date_heure', '>=', today())
            ->whereDate('date_heure', '<=', today()->addDays(7))
            
            ->orderBy('date_heure')
            ->get();

        // Statistiques mensuelles pour le graphique (patients seulement)
        $patientsMensuels = $this->getPatientsMensuels();

        return view('dashboard', compact(
            'stats',
            'rdvAujourdhuiList',
            'stockAlerteList',
            'fournisseurs',
            'rdvProchains',
            'patientsMensuels'
        ));
    }

    /**
     * Récupère les statistiques mensuelles des patients
     */
    private function getPatientsMensuels()
    {
        $currentYear = now()->year;
        
        return Patient::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
    }

    /**
     * API pour les données du graphique
     */
    public function getChartData()
    {
        $patients = $this->getPatientsMensuels();

        // Compléter les mois manquants avec 0
        $moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $patientsData = [];

        for ($mois = 1; $mois <= 12; $mois++) {
            $patientsData[] = $patients[$mois] ?? 0;
        }

        return response()->json([
            'labels' => $moisLabels,
            'patients' => $patientsData,
        ]);
    }
}

