<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Cabinet;
use App\Modules\Auth\Models\User;
use App\Modules\Fournisseur\Models\Fournisseur;
use App\Modules\Patient\Models\Patient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('super_admin_dashboard_stats', 300, function () {
            $totalUsers = User::count();
            $activeUsers = User::where('actif', true)->count();
            $inactiveUsers = User::where('actif', false)->count();
            $totalPatients = Patient::count();
            $newPatients = Patient::where('created_at', '>=', now()->subDays(30))->count();
            $totalMedecins = User::whereIn('role', ['medecin', 'dentiste'])->count();
            $medecinsActifs = User::whereIn('role', ['medecin', 'dentiste'])->where('actif', true)->count();
            $totalSecretaires = User::whereIn('role', ['secretaire', 'assistant'])->count();
            $totalFournisseurs = Fournisseur::count();
            $totalCabinets = Schema::hasTable('cabinets') ? Cabinet::count() : 0;
            $totalAdmins = User::whereIn('role', ['super_admin', 'admin_cabinet', 'admin'])->count();
            $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();

            return compact(
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'totalPatients',
                'newPatients',
                'totalMedecins',
                'medecinsActifs',
                'totalSecretaires',
                'totalFournisseurs',
                'totalCabinets',
                'totalAdmins',
                'newUsersThisMonth'
            );
        });

        $evolution = Cache::remember('super_admin_user_evolution', 3600, function () {
            $labels = [];
            $data = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d/m');
                $data[] = User::whereDate('created_at', $date)->count();
            }

            return compact('labels', 'data');
        });

        $recentUsers = User::latest()->limit(10)->get();

        $recentActivities = AuditLog::latest()->limit(8)->get()->map(function ($log) {
            $user = User::find($log->user_id);

            return (object) [
                'description' => $log->action ?? 'Activite',
                'created_at' => $log->created_at,
                'icon' => 'history',
                'user' => $user,
            ];
        });

        return view('super_admin.dashboard', array_merge(
            $stats,
            [
                'evolutionLabels' => $evolution['labels'],
                'evolutionData' => $evolution['data'],
                'recentUsers' => $recentUsers,
                'recentActivities' => $recentActivities,
            ]
        ));
    }
}
