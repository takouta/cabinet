<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $medecinId = Auth::id();

        $prochains_rdv = RendezVous::with('patient')
            ->where('dentiste_id', $medecinId)
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->limit(10)
            ->get();

        $rdv_aujourdhui = RendezVous::with('patient')
            ->where('dentiste_id', $medecinId)
            ->whereDate('date_heure', today())
            ->orderBy('date_heure')
            ->get();

        $patientsTotal = RendezVous::where('dentiste_id', $medecinId)
            ->distinct('patient_id')
            ->count('patient_id');

        $stats = [
            'patients_total' => $patientsTotal,
            'rdv_mois' => RendezVous::where('dentiste_id', $medecinId)
                ->whereMonth('date_heure', now()->month)
                ->whereYear('date_heure', now()->year)
                ->count(),
            'taux_absentisme' => $this->calculerTauxAbsentisme($medecinId),
        ];

        return view('medecin.dashboard', compact('prochains_rdv', 'rdv_aujourdhui', 'stats'));
    }

    private function calculerTauxAbsentisme(int $medecinId): float
    {
        $total = RendezVous::where('dentiste_id', $medecinId)->count();
        if ($total === 0) {
            return 0;
        }

        $absents = RendezVous::where('dentiste_id', $medecinId)
            ->whereIn('statut', ['non_presente', 'absent'])
            ->count();

        return round(($absents / $total) * 100, 2);
    }
}
