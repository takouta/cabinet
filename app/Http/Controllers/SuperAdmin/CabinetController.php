<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index()
    {
        $cabinets = Cabinet::orderBy('nom')->paginate(12);

        return view('super_admin.cabinets.index', compact('cabinets'));
    }

    public function create()
    {
        return view('super_admin.cabinets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'bail|required|digits_between:8,30',
            'email' => 'nullable|email|max:255',
        ], [
            'telephone.required' => 'Le telephone est obligatoire.',
            'telephone.digits_between' => 'Le telephone doit contenir uniquement des chiffres (8 a 30).',
        ]);

        Cabinet::create([
            'nom' => $validated['nom'],
            'adresse' => $validated['adresse'],
            'telephone' => $validated['telephone'],
            'email' => $validated['email'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('super_admin.cabinets.index')
            ->with('success', 'Cabinet cree avec succes.');
    }

    public function show($id)
    {
        $cabinet = Cabinet::findOrFail($id);

        return view('super_admin.cabinets.show', compact('cabinet'));
    }

    public function edit($id)
    {
        $cabinet = Cabinet::findOrFail($id);

        return view('super_admin.cabinets.edit', compact('cabinet'));
    }

    public function update(Request $request, $id)
    {
        $cabinet = Cabinet::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'bail|required|digits_between:8,30',
            'email' => 'nullable|email|max:255',
        ], [
            'telephone.required' => 'Le telephone est obligatoire.',
            'telephone.digits_between' => 'Le telephone doit contenir uniquement des chiffres (8 a 30).',
        ]);

        $cabinet->update($validated);

        return redirect()->route('super_admin.cabinets.show', $cabinet->id)
            ->with('success', 'Cabinet mis a jour.');
    }

    public function destroy($id)
    {
        $cabinet = Cabinet::findOrFail($id);
        $cabinet->delete();

        return redirect()->route('super_admin.cabinets.index')
            ->with('success', 'Cabinet supprime.');
    }
}
