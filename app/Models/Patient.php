<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $table = 'patients';
    
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'numero_securite_sociale',
        'mutuelle',
        'adresse',
        'telephone',
        'antecedents_medicaux',
        'allergies',
        'groupe_sanguin',
        'profession',
        'contact_urgence'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'antecedents_medicaux' => 'array',
        'allergies' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getAgeAttribute(): int
    {
        return $this->date_naissance ? $this->date_naissance->age : 0;
    }
}