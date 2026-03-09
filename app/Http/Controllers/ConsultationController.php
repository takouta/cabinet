<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\RendezVous\Models\RendezVous;
use App\Modules\Patient\Models\Patient;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = RendezVous::with(['patient', 'dentiste'])
            ->whereIn('statut', ['confirmé', 'terminé'])
            ->orderBy('date_heure', 'desc')
            ->get();
            
        return view('consultations.index', compact('consultations'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nom')->get();
        return view('consultations.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'type_consultation' => 'required|in:première_visite,contrôle,traitement,urgence',
            'observations' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'traitement_effectue' => 'nullable|string'
        ]);

        RendezVous::create(array_merge($validated, [
            'dentiste_id' => auth()->id(),
            'statut' => 'confirmé'
        ]));

        return redirect()->route('consultations.index')
                        ->with('success', 'Consultation créée avec succès!');
    }

    public function show(RendezVous $consultation)
    {
        return view('consultations.show', compact('consultation'));
    }

    public function edit(RendezVous $consultation)
    {
        $patients = Patient::orderBy('nom')->get();
        return view('consultations.edit', compact('consultation', 'patients'));
    }

    public function update(Request $request, RendezVous $consultation)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'type_consultation' => 'required|in:première_visite,contrôle,traitement,urgence',
            'observations' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'traitement_effectue' => 'nullable|string'
        ]);

        $consultation->update($validated);

        return redirect()->route('consultations.index')
                        ->with('success', 'Consultation modifiée avec succès!');
    }

    public function destroy(RendezVous $consultation)
    {
        $consultation->delete();

        return redirect()->route('consultations.index')
                        ->with('success', 'Consultation supprimée avec succès!');
    }

    public function completerConsultation(RendezVous $consultation)
    {
        return view('consultations.completer', compact('consultation'));
    }

    public function storeCompletement(Request $request, RendezVous $consultation)
    {
        $validated = $request->validate([
            'observations' => 'required|string',
            'diagnostic' => 'required|string',
            'traitement_effectue' => 'required|string',
            'prescription' => 'nullable|string',
            'prochaine_visite' => 'nullable|date|after:today'
        ]);

        $consultation->update(array_merge($validated, [
            'statut' => 'terminé'
        ]));

        return redirect()->route('consultations.index')
                        ->with('success', 'Consultation complétée avec succès!');
    }
}
