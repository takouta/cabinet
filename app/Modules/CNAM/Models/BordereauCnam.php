<?php

namespace App\Modules\CNAM\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Patient\Models\Patient;
use App\Models\User;

class BordereauCnam extends Model
{
    protected $table = 'bordereaux_cnam';

    protected $fillable = [
        'numero_bs',
        'dentiste_id',
        'date_bordereau',
        'montant_total',
        'statut',
        'notes'
    ];

    public function dentiste()
    {
        return $this->belongsTo(User::class, 'dentiste_id');
    }

    public function soins()
    {
        return $this->hasMany(Soin::class, 'bordereau_id');
    }
}
