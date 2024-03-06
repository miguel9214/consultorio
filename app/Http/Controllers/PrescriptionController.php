<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Pacient;
use App\Models\Consultation;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class PrescriptionController extends Controller
{
    public function index()
    {
        $prescription = Prescription::all();

        return response()->json(['message' => 'List prescription', 'data' => $prescription]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_prescription' => 'required|date',
            'dose' => 'required|string',
            'treatment' => 'required|string',
            'additional_instructions' => 'required|string',
        ]);

        $prescription = Prescription::create([
            'dosage' => $request->input('dosage'),
            'observacion' => $request->input('observacion'),
            'diagnosis' => $request->input('diagnosis'),
        ]);
        $prescription->save();


        if ($prescription) {
            return response()->json(['message' => 'Prescription created successfully']);
        } else {
            return response()->json(['message' => 'Error in created the Prescription']);
        }
    }

    public function show(string $id)
    {
        $prescription = Prescription::find($id);

        if ($prescription) {
            return response()->json(['message' => 'Prescription found', 'data' => $prescription]);
        } else {
            return response()->json(['message' => 'Prescription not found']);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'dosage' => 'required|string',
            'observacion' => 'required|date',
            'diagnosis' => 'required|string',
        ]);

        try {
            $prescription = Prescription::find($id);
            $prescription->dosage = $request->dosage;
            $prescription->observacion = $request->observacion;
            $prescription->diagnosis = $request->diagnosis;
            $prescription->save();

            return response()->json(['message' => 'Prescription updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error in updated the Prescription: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {

            return response()->json(['message' => 'Prescription not found']);
        } else {

            $prescription->delete();

            return response()->json(['message' => 'Prescription deleted successfully']);
        }
    }
}
