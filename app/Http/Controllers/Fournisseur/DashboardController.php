<?php
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

        // Fournisseur lié à ce compte (par email)
        $fournisseur = Fournisseur::where('email', $user->email)
            ->with(['produits', 'cabinets'])
            ->first();

        if (!$fournisseur) {
            return view('fournisseur.dashboard', [
                'fournisseur'      => null,
                'produits'         => collect(),
                'cabinets'         => collect(),
                'stocks_cabinet'   => collect(),
                'stats'            => ['total_produits' => 0, 'total_cabinets' => 0, 'produits_alerte' => 0],
            ]);
        }

        // Produits du catalogue de ce fournisseur
        $produits = $fournisseur->produits()->orderBy('nom')->get();

        // Cabinets clients de ce fournisseur
        $cabinets = $fournisseur->cabinets()->orderBy('nom')->get();

        // Stock du cabinet lié à ce fournisseur (pour info)
        $stocks_cabinet = StockMatierePremiere::where('fournisseur_id', $fournisseur->id)
            ->orderBy('nom')
            ->get();

        $alertes = $stocks_cabinet->filter(fn($s) => ($s->quantite ?? 0) <= ($s->seuil_alerte ?? 5))->count();

        $stats = [
            'total_produits'   => $produits->count(),
            'total_cabinets'   => $cabinets->count(),
            'produits_alerte'  => $alertes,
        ];

        return view('fournisseur.dashboard', [
            'fournisseur'    => $fournisseur,
            'produits'       => $produits,
            'cabinets'       => $cabinets,
            'stocks_cabinet' => $stocks_cabinet,
            'stats'          => $stats,
        ]);
    }
}