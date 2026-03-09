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
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Modules\Auth\Models\User::class);
    }

    public function rendezvous()
    {
        return $this->hasMany(RendezVous::class);
    }
}