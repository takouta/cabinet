<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;

    protected $table = 'sms';

    protected $fillable = [
        'numero_destinataire',
        'message',
        'type',
        'statut',
        'envoye_a'
    ];
}
