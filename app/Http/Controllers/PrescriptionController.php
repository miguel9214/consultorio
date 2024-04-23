<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Pacient;
use App\Models\Medicine;
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
            'consultation_id' => 'required|integer', 
            'medicines' => 'required|array', 
            'medicines.*.medicine_id' => 'required|integer', 
            'medicines.*.dose' => 'required|string',
            'medicines.*.treatment' => 'required|string',
            'medicines.*.additional_instructions' => 'required|string', 
        ]);

        try {    
            foreach ($request->medicines as $medicineData) {
                $existingPrescription = Prescription::where('consultation_id', $request->consultation_id)
                                                    ->where('medicine_id', $medicineData['medicine_id'])
                                                    ->exists();
                                                    
                if ($existingPrescription) {
                    return response()->json(['message' => 'El medicamento ya ha sido prescrito para esta consulta'], 400);
                }
                
                $prescription = new Prescription();
                $prescription->date_prescription = $request->date_prescription;
                $prescription->consultation_id = $request->consultation_id; 
                $prescription->medicine_id = $medicineData['medicine_id'];
                $prescription->dose = $medicineData['dose'];
                $prescription->treatment = $medicineData['treatment'];
                $prescription->additional_instructions = $medicineData['additional_instructions'];
                $prescription->save();
            }

            return response()->json(['message' => 'Prescriptions created successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating the Prescriptions: ' . $e->getMessage()], 500);
        }
    }
    
    public function show(string $id)
    {
        $data = DB::table('medicines as m')
        ->join('prescriptions as p', 'm.id', 'p.medicine_id')
        ->where('p.consultation_id', $id)
        ->select(
            'm.code as medicine_code',
            'm.name as medicine_name', 
            'p.dose', 
            'p.treatment', 
            'p.additional_instructions',
            'm.id as medicine_id',
            'p.id as prescription_id',
        )->get();

        if ($data) {
            return response()->json(['message' => 'Prescription found', 'data' => $data]);
        } else {
            return response()->json(['message' => 'Prescription not found']);
        }
    }

    public function showPrescription(string $id)
    {
        $data = DB::table('medicines as m')
        ->join('prescriptions as p', 'm.id', 'p.medicine_id')
        ->where('p.consultation_id', $id)
        ->select(
            'm.code as medicine_code',
            'm.name as medicine_name', 
            'p.dose', 
            'p.treatment', 
            'p.additional_instructions',
            'p.date_prescription',
            'p.id as prescription_id',
            'p.medicine_id as medicine_id',
            'p.consultation_id as consultation_id',
        )->get();

        if ($data) {
            return response()->json(['message' => 'Prescription found - viewPrescription', 'data' => $data]);
        } else {
            return response()->json(['message' => 'Prescription not found - viewPrescription']);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'date_prescription' => 'required|date',
            'medicines.*.dose' => 'required|string',
            'medicines.*.treatment' => 'required|string',
            'medicines.*.additional_instructions' => 'required|string',
        ]);
    
        try {
            foreach ($request->medicines as $medicineData) {
                $prescription = Prescription::findOrFail($id);
                
                $prescription->date_prescription = $request->date_prescription;
                $prescription->dose = $medicineData['dose'];
                $prescription->treatment = $medicineData['treatment'];
                $prescription->additional_instructions = $medicineData['additional_instructions'];
                
                $prescription->save();
            }
    
            return response()->json(['message' => 'Prescriptions updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating the Prescriptions: ' . $e->getMessage()], 500);
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
