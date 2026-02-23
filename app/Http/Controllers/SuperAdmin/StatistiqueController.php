<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Modules\Auth\Models\User;
use App\Modules\Fournisseur\Models\Fournisseur;
use App\Modules\Patient\Models\Patient;

class StatistiqueController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalPatients' => Patient::count(),
            'totalMedecins' => User::whereIn('role', ['medecin', 'dentiste'])->count(),
            'totalSecretaires' => User::whereIn('role', ['secretaire', 'assistant'])->count(),
            'totalFournisseurs' => Fournisseur::count(),
            'totalCabinets' => Cabinet::count(),
        ];

        return view('super_admin.statistiques.index', compact('stats'));
    }

    public function rapports()
    {
        return view('super_admin.statistiques.rapports');
    }
}
