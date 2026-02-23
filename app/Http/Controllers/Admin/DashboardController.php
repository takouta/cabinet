<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use App\Modules\Patient\Models\Patient;
use App\Modules\RendezVous\Models\RendezVous;
use App\Modules\Stock\Models\StockMatierePremiere;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques
        $stats = [
            'patients_total' => Patient::count(),
            'rendez_vous_aujourdhui' => RendezVous::whereDate('date_heure', today())->count(),
            'produits_alerte' => $this->getProduitsAlerte(),
            'bs_en_attente' => $this->getBsEnAttenteCount(),
        ];

        // Rendez-vous du jour avec relations
        $rendez_vous_aujourdhui = RendezVous::with(['patient', 'dentiste'])
            ->whereDate('date_heure', today())
            ->orderBy('date_heure')
            ->get();

        // Produits en alerte
        $produits_alerte = $this->getProduitsAlerteDetails();

        // Données pour les graphiques
        $rdv_semaine = $this->getRdvSemaine();
        $top_dentistes = $this->getTopDentistes();

        return view('admin.dashboard', compact(
            'stats',
            'rendez_vous_aujourdhui',
            'produits_alerte',
            'rdv_semaine',
            'top_dentistes'
        ));
    }

    private function getProduitsAlerte()
    {
        // Logique pour compter les produits en alerte
        return StockMatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')->count();
    }

    private function getProduitsAlerteDetails()
    {
        // Logique pour récupérer les détails des produits en alerte
        return StockMatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')
            ->orderBy('quantite')
            ->limit(5)
            ->get();
    }

    private function getRdvSemaine()
    {
        // Logique pour les rendez-vous de la semaine
        $rdvParJour = [];
        for ($i = 0; $i < 6; $i++) {
            $date = now()->startOfWeek()->addDays($i);
            $rdvParJour[] = RendezVous::whereDate('date_heure', $date)->count();
        }
        return $rdvParJour;
    }

    private function getTopDentistes()
    {
        // Logique pour les top dentistes du mois
        return User::where('role', 'medecin')
            ->withCount(['rendezvous as total' => function($query) {
                $query->whereMonth('date_heure', now()->month)
                      ->whereYear('date_heure', now()->year);
            }])
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($medecin) {
                return [
                    'dentiste_id' => $medecin->id,
                    'nom' => $medecin->nom,
                    'prenom' => $medecin->prenom,
                    'total' => $medecin->total,
                ];
            });
    }

    private function getBsEnAttenteCount(): int
    {
        $modelClass = 'App\\Modules\\Facturation\\Models\\BulletinSoins';

        if (class_exists($modelClass)) {
            return $modelClass::where('statut_transmission', 'en_attente')->count();
        }

        if (Schema::hasTable('bulletin_soins')) {
            return DB::table('bulletin_soins')
                ->where('statut_transmission', 'en_attente')
                ->count();
        }

        return 0;
    }
}
