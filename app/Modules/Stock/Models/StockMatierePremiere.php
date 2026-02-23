<?php

namespace App\Modules\Stock\Models;

use App\Modules\Fournisseur\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMatierePremiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'quantite',
        'unite_mesure',
        'seuil_alerte',
        'fournisseur_id',
        'prix_unitaire',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}