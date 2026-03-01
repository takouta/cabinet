<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function envoyerSMS($numero, $message)
    {
        try {
            $this->twilio->messages->create(
                $numero,
                [
                    'from' => config('services.twilio.phone_number'),
                    'body' => $message
                ]
            );
            Log::info("SMS envoyé à $numero");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi SMS: " . $e->getMessage());
            return false;
        }
    }

    public function rappelRendezVous($patient, $rendezVous)
    {
        $message = "Rappel: Rendez-vous le " . 
                   $rendezVous->date_heure->format('d/m/Y à H:i') . 
                   " avec Dr. " . $rendezVous->dentiste->name;

        return $this->envoyerSMS($patient->telephone, $message);
    }

    public function alerteStockBas($fournisseur, $stock)
    {
        $message = "Alerte stock: " . $stock->nom . 
                   " en quantité basse (" . $stock->quantite . 
                   $stock->unite_mesure . "). Commande nécessaire.";

        return $this->envoyerSMS($fournisseur->telephone, $message);
    }
}
