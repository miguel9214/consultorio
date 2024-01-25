<?php

namespace App\Http\Controllers;

use App\Models\ConsultationType;  
use App\Models\Medico;            
use App\Models\Pacient;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class ConsultationController extends Controller
{
    public function index()
    {
        $consultationList = Consultation::all();

        return response()->json(['message' => 'List of Consultation', 'data' => $consultationList]);
    }

    public function indexPublic()
    {
        $consultationList = Consultation::all();

        return response()->json(['message' => 'List of Consultation', 'data' => $consultationList]);
    }

    public function show(string $id)
    {
        $consultation = Consultation::find($id);

        if ($consultation) {
            return response()->json(['message' => 'Consultation found', 'data' => $consultation]);
        } else {
            return response()->json(['message' => 'Consultation not found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'observation' => 'required|string',
            'status' => 'required|string',
            'date' => 'required|string',
            'consultation_type_id' => 'required|int',
            'doctor_id' => 'required|int',
            'pacient_id' => 'required|int'
        ]);

        try {
        
            $consultation = new Consultation();
            $consultation->observation = $request->observation;
            $consultation->status = $request->status;
            $consultation->date = $request->date;   
            $consultation->consultation_type_id = $request->consultation_type_id;
            $consultation->doctor_id = $request->doctor_id;
            $consultation->pacient_id = $request->pacient_id;          
            $consultation->created_by_user = Auth::id();
            $consultation->save();

            return response()->json(['message' => 'Consultation created successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating Consultation: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'observation' => 'required|string',
            'status' => 'required|string',
            'date' => 'required|string',
            'consultation_type_id' => 'required|int',
            'doctor_id' => 'required|int',
            'pacient_id' => 'required|int'
        ]);

        try {
            $consultation = Consultation::find($id);
            $consultation->observation = $request->observation;
            $consultation->status = $request->status;
            $consultation->date = $request->date;   
            $consultation->consultation_type_id = $request->consultation_type_id;
            $consultation->doctor_id = $request->doctor_id;
            $consultation->pacient_id = $request->pacient_id;          
            $consultation->updated_by_user = Auth::id();
            $consultation->save();

            return response()->json(['message' => 'Consultation updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating Consultation: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not delete']);
        }

        $consultation->delete();

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
