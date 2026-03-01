<?php

namespace App\Modules\Auth\Models;

use App\Models\AuditLog;
use App\Models\Cabinet;
use App\Models\Configuration;
use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected static function booted()
    {
        static::saving(function ($user) {
            // Synchronisation : name = prenom + nom
            if (empty($user->name) && (!empty($user->nom) || !empty($user->prenom))) {
                $user->name = trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''));
            }
            // Inverse : si name est rempli mais pas nom/prenom (cas RegisterController)
            elseif (!empty($user->name) && (empty($user->nom) || empty($user->prenom))) {
                $parts = preg_split('/\s+/', trim($user->name)) ?: [];
                if (empty($user->prenom)) {
                    $user->prenom = $parts[0] ?? '';
                }
                if (empty($user->nom)) {
                    $user->nom = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : ($user->prenom ?: '');
                }
            }
        });
    }

    protected $fillable = [
        'nom',
        'prenom',
        'name',
        'email',
        'password',
        'adresse',
        'role',
        'actif',
        'derniere_connexion',
        'specialite',
        'telephone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'actif' => 'boolean',
        'derniere_connexion' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifier si l'utilisateur a un rôle spécifique.
     */
    public function aRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur est admin cabinet.
     */
    public function estAdminCabinet(): bool
    {
        return $this->role === 'admin_cabinet';
    }

    /**
     * Vérifier si l'utilisateur est médecin.
     */
    public function estMedecin(): bool
    {
        return $this->role === 'medecin';
    }

    /**
     * Vérifier si l'utilisateur est secrétaire.
     */
    public function estSecretaire(): bool
    {
        return $this->role === 'secretaire';
    }

    /**
     * Vérifier si l'utilisateur est patient.
     */
    public function estPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * Vérifier si l'utilisateur est fournisseur.
     */
    public function estFournisseur(): bool
    {
        return $this->role === 'fournisseur';
    }

    /**
     * Vérifier si l'utilisateur est super admin.
     */
    public function estSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function rendezvous()
    {
        return $this->hasMany(RendezVous::class, 'dentiste_id');
    }

    public function cabinetsGerer()
    {
        return $this->hasMany(Cabinet::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'updated_by');
    }

    public function fournisseur()
    {
        return $this->hasOne(\App\Modules\Fournisseur\Models\Fournisseur::class, 'email', 'email');
    }
}
