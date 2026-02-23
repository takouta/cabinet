<?php

namespace App\Modules\RendezVous\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezvous = RendezVous::with('patient')->orderBy('date_heure', 'desc')->get();
        return view('rendezvous.index', compact('rendezvous'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nom')->get();
        return view('rendezvous.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'statut' => 'required|in:confirme,annule,en_attente',
            'type_consultation' => 'required|in:premiere_visite,controle,traitement,urgence',
        ]);

        $validated['dentiste_id'] = Auth::id() ?? 1;

        RendezVous::create($validated);

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous cree avec succes!');
    }

    public function show(RendezVous $rendezvous)
    {
        return view('rendezvous.show', compact('rendezvous'));
    }

    public function edit(RendezVous $rendezvous)
    {
        $patients = Patient::orderBy('nom')->get();
        return view('rendezvous.edit', compact('rendezvous', 'patients'));
    }

    public function update(Request $request, RendezVous $rendezvous)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'statut' => 'required|in:confirme,annule,en_attente',
            'type_consultation' => 'required|in:premiere_visite,controle,traitement,urgence',
        ]);

        $rendezvous->update($validated);

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous modifie avec succes!');
    }

    public function destroy(RendezVous $rendezvous)
    {
        $rendezvous->delete();

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous supprime avec succes!');
    }
}