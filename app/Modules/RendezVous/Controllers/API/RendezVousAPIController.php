<?php

namespace App\Modules\RendezVous\Controllers\API;

use App\Http\Controllers\Controller;
use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RendezVousAPIController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(RendezVous::with('patient')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'type_consultation' => 'required|string|max:255',
            'statut' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rdv = RendezVous::create($request->all());

        return response()->json($rdv, 201);
    }

    public function show(string $id): JsonResponse
    {
        $rdv = RendezVous::with('patient')->find($id);

        if (!$rdv) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }

        return response()->json($rdv);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $rdv = RendezVous::find($id);

        if (!$rdv) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }

        $rdv->update($request->all());

        return response()->json($rdv);
    }

    public function destroy(string $id): JsonResponse
    {
        $rdv = RendezVous::find($id);

        if (!$rdv) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }

        $rdv->delete();

        return response()->json(['message' => 'Rendez-vous deleted']);
    }
}