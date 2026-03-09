<?php

namespace App\Modules\RendezVous\Models;

use App\Modules\Auth\Models\User;
use App\Modules\Patient\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'patient_id',
        'dentiste_id',
        'date_heure',
        'type_consultation',
        'statut',
        'notes',
        'motif',
        'observations',
        'diagnostic',
        'traitement_effectue',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentiste()
    {
        return $this->belongsTo(User::class, 'dentiste_id');
    }
}