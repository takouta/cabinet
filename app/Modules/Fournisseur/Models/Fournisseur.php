<?php

namespace App\Modules\Fournisseur\Models;

use App\Modules\Stock\Models\StockMatierePremiere;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'specialite',
    ];

    public function stocks()
    {
        return $this->hasMany(StockMatierePremiere::class);
    }

    public function produits()
    {
        return $this->hasMany(ProduitFournisseur::class);
    }

    public function cabinets()
    {
        return $this->belongsToMany(\App\Models\Cabinet::class, 'cabinet_fournisseur', 'fournisseur_id', 'cabinet_id')
                    ->withPivot(['date_debut', 'notes'])
                    ->withTimestamps();
    }

    public function factures()
    {
        return $this->hasMany(FactureFournisseur::class);
    }
}