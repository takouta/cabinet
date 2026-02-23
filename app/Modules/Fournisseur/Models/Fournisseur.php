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
}