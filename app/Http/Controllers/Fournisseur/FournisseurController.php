<?php

namespace App\Http\Controllers\Fournisseur;

use App\Http\Controllers\Controller;
use App\Modules\Stock\Models\Fournisseur;
use App\Modules\Stock\Models\Produit;
use App\Modules\Stock\Models\Commande;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin_cabinet,super_admin,secretaire,fournisseur']);
    }

    /**
     * Afficher le dashboard fournisseur
     */
    public function index()
    {
        $fournisseur = auth()->user()->fournisseur;
        
        if (!$fournisseur) {
            return view('fournisseur.dashboard', [
                'fournisseur' => null,
                'stats' => [],
                'produits' => [],
                'commandes' => []
            ]);
        }

        // Statistiques
        $stats = [
            'total_produits' => Produit::where('fournisseur_id', $fournisseur->id)->count(),
            'commandes_en_cours' => Commande::where('fournisseur_id', $fournisseur->id)
                ->whereIn('statut', ['en_attente', 'confirmee'])
                ->count(),
            'produits_alerte' => $this->getProduitsAlerte($fournisseur->id),
            'chiffre_affaires' => Commande::where('fournisseur_id', $fournisseur->id)
                ->where('statut', 'livree')
                ->sum('montant_total')
        ];

        // Produits récents
        $produits = Produit::where('fournisseur_id', $fournisseur->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Commandes récentes
        $commandes = Commande::where('fournisseur_id', $fournisseur->id)
            ->with('produits')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('fournisseur.dashboard', compact('fournisseur', 'stats', 'produits', 'commandes'));
    }

    /**
     * Liste des produits du fournisseur
     */
    public function produits()
    {
        $fournisseur = auth()->user()->fournisseur;
        $produits = Produit::where('fournisseur_id', $fournisseur->id)
            ->orderBy('nom')
            ->paginate(15);

        return view('fournisseur.produits.index', compact('produits'));
    }

    /**
     * Liste des commandes
     */
    public function commandes()
    {
        $fournisseur = auth()->user()->fournisseur;
        $commandes = Commande::where('fournisseur_id', $fournisseur->id)
            ->with('produits')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('fournisseur.commandes.index', compact('commandes'));
    }

    /**
     * Détails d'une commande
     */
    public function showCommande($id)
    {
        $commande = Commande::with(['produits', 'fournisseur'])
            ->where('id', $id)
            ->where('fournisseur_id', auth()->user()->fournisseur->id)
            ->firstOrFail();

        return view('fournisseur.commandes.show', compact('commande'));
    }

    /**
     * Confirmer une commande
     */
    public function confirmerCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->statut = 'confirmee';
        $commande->save();

        return redirect()->back()->with('success', 'Commande confirmée avec succès');
    }

    /**
     * Marquer une commande comme expédiée
     */
    public function expedierCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->statut = 'expediee';
        $commande->date_expedition = now();
        $commande->save();

        return redirect()->back()->with('success', 'Commande marquée comme expédiée');
    }

    /**
     * Profil du fournisseur
     */
    public function profil()
    {
        $fournisseur = auth()->user()->fournisseur;
        return view('fournisseur.profil', compact('fournisseur'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfil(Request $request)
    {
        $fournisseur = auth()->user()->fournisseur;
        
        $request->validate([
            'nom_societe' => 'required|string|max:255',
            'nom_contact' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
        ]);

        $fournisseur->update($request->all());

        return redirect()->back()->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Obtenir les produits en alerte
     */
    private function getProduitsAlerte($fournisseurId)
    {
        return Produit::where('fournisseur_id', $fournisseurId)
            ->get()
            ->filter(function($produit) {
                return ($produit->stock_actuel ?? 0) <= ($produit->seuil_alerte ?? 5);
            })
            ->count();
    }
}