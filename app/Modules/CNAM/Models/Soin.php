<?php

namespace App\Modules\CNAM\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Patient\Models\Patient;
use App\Models\User;

class Soin extends Model
{
    protected $fillable = [
        'patient_id',
        'dentiste_id',
        'bordereau_id',
        'acte_code',
        'designation',
        'date_soin',
        'montant',
        'part_cnam',
        'part_patient',
        'statut'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentiste()
    {
        return $this->belongsTo(User::class, 'dentiste_id');
    }

    public function bordereau()
    {
        return $this->belongsTo(BordereauCnam::class, 'bordereau_id');
    }
}
