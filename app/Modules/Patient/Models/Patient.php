<?php

namespace App\Modules\Patient\Models;

use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'adresse',
        'antecedents_medicaux',
    ];

    public function rendezvous()
    {
        return $this->hasMany(RendezVous::class);
    }
}