<?php
// app/Http/Controllers/Fournisseur/DashboardController.php

namespace App\Http\Controllers\Fournisseur;

use App\Http\Controllers\Controller;
use App\Modules\Fournisseur\Models\Fournisseur;
use App\Modules\Stock\Models\StockMatierePremiere;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Chercher le fournisseur par email de l'utilisateur connecté
        $fournisseur = Fournisseur::where('email', $user->email)->first();

        if (!$fournisseur) {
            return view('fournisseur.dashboard', [
                'fournisseur'       => null,
                'stats'             => [],
                'produits_recents'  => collect(),
                'a_approvisionner'  => collect(),
                'dernieres_commandes' => collect(),
                'commandes_labels'  => [],
                'commandes_data'    => [],
                'categories_labels' => [],
                'categories_data'   => [],
            ]);
        }

        // Stocks liés à ce fournisseur
        $stocks = StockMatierePremiere::where('fournisseur_id', $fournisseur->id)
            ->orderBy('nom')
            ->get();

        // Produits à approvisionner (stock <= seuil)
        $a_approvisionner = $stocks->filter(function ($s) {
            return ($s->quantite ?? 0) <= ($s->seuil_alerte ?? 5);
        })->take(5)->values();

        // Statistiques
        $stats = [
            'total_produits'     => $stocks->count(),
            'commandes_en_cours' => 0, // Table commandes inexistante pour l'instant
            'produits_alerte'    => $a_approvisionner->count(),
            'chiffre_affaires'   => 0,
        ];

        // Graphique catégories via unité de mesure (approximation)
        $categories = $stocks->groupBy('unite_mesure')
            ->map(fn($group) => $group->count());

        return view('fournisseur.dashboard', [
            'fournisseur'         => $fournisseur,
            'stats'               => $stats,
            'produits_recents'    => $stocks->take(6)->values(),
            'a_approvisionner'    => $a_approvisionner,
            'dernieres_commandes' => collect(),
            'commandes_labels'    => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            'commandes_data'      => [0, 0, 0, 0, 0, 0],
            'categories_labels'   => $categories->keys()->toArray(),
            'categories_data'     => $categories->values()->toArray(),
        ]);
    }
}