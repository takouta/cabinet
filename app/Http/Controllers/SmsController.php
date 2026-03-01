<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sms;
use App\Modules\Patient\Models\Patient;
use App\Modules\Fournisseur\Models\Fournisseur;

class SmsController extends Controller  // ââ€ Â ChangÃƒÂ© de "SmsController" ÃƒÂ  "SmsController"
{
    public function index()
    {
        $sms = Sms::orderBy('created_at', 'desc')->get();
        $patients = Patient::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        
        return view('sms.index', compact('sms', 'patients', 'fournisseurs'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('sms.create', compact('patients', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_destinataire' => 'required|string|max:20',
            'message' => 'required|string|max:500',
            'type' => 'required|in:rappel_rdv,alerte_stock,promotion,autre'
        ]);

        Sms::create(array_merge($validated, [
            'statut' => 'en_attente'
        ]));

        return redirect()->route('sms.index')
                        ->with('success', 'SMS programmÃƒÂ© avec succÃƒÂ¨s!');
    }

    public function show(Sms $sms)
    {
        return view('sms.show', compact('sms'));
    }

    public function destroy(Sms $sms)
    {
        $sms->delete();

        return redirect()->route('sms.index')
                        ->with('success', 'SMS supprimÃƒÂ© avec succÃƒÂ¨s!');
    }

    public function sendRappelRdv(Request $request)
    {
        $validated = $request->validate([
            'patient_ids' => 'required|array',
            'patient_ids.*' => 'exists:patients,id',
            'message' => 'required|string|max:500'
        ]);

        foreach ($validated['patient_ids'] as $patientId) {
            $patient = Patient::find($patientId);
            
            Sms::create([
                'numero_destinataire' => $patient->telephone,
                'message' => $validated['message'],
                'type' => 'rappel_rdv',
                'statut' => 'en_attente'
            ]);
        }

        return redirect()->route('sms.index')
                        ->with('success', 'Rappels RDV programmÃƒÂ©s avec succÃƒÂ¨s!');
    }

    public function sendAlerteStock(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_ids' => 'required|array',
            'fournisseur_ids.*' => 'exists:fournisseurs,id',
            'message' => 'required|string|max:500'
        ]);

        foreach ($validated['fournisseur_ids'] as $fournisseurId) {
            $fournisseur = Fournisseur::find($fournisseurId);
            
            Sms::create([
                'numero_destinataire' => $fournisseur->telephone,
                'message' => $validated['message'],
                'type' => 'alerte_stock',
                'statut' => 'en_attente'
            ]);
        }

        return redirect()->route('sms.index')
                        ->with('success', 'Alertes stock envoyÃƒÂ©es avec succÃƒÂ¨s!');
    }
}

