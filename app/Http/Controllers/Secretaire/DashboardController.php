<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\RendezVous\Models\RendezVous;

class DashboardController extends Controller
{
    public function index()
    {
        $a_confirmer = RendezVous::whereIn('statut', ['planifie', 'en_attente'])
            ->where('date_heure', '>', now())
            ->with(['patient', 'dentiste'])
            ->orderBy('date_heure')
            ->limit(10)
            ->get();

        $agenda_jour = RendezVous::with(['patient', 'dentiste'])
            ->whereDate('date_heure', today())
            ->orderBy('date_heure')
            ->get();

        $nouveaux_patients = Patient::latest()->limit(5)->get();

        return view('secretaire.dashboard', compact('a_confirmer', 'agenda_jour', 'nouveaux_patients'));
    }
}
