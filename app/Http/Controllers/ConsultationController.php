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
use Illuminate\Support\Carbon;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class ConsultationController extends Controller
{
    public function index()
    {
        $data = DB::table("consultations as c")
            ->join("consultation_types as tc", "tc.id", "c.consultation_type_id")
            ->join("doctors as d", "d.id", "c.doctor_id")
            ->join("persons as p", "p.id", "d.person_id")
            ->join("patients as pc", "pc.id", "c.pacient_id")
            ->join("persons as ps", "ps.id", "pc.person_id")
            ->join("users as us", "us.id", "ps.user_id")
            ->join("invoices as in", "c.id", "in.consultation_id")
            ->select(
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as doctor"),
                DB::raw("CONCAT(ps.first_name, ' ', ps.last_name) as paciente"),
                "c.id",                
                "c.date as fecha",
                "c.hour as hora",
                "c.observation as observacion",
                "c.status as estado",
                "tc.name as tipo_consulta",
                "ps.address as direccion",
                "us.email as correo",
                "in.invoice_number as invoice_number",
                "in.start_date as start_date",
                "in.due_date as due_date",
                "in.status as status",
                "in.total_amount as total_amount",
                "in.taxes as taxes",
                "in.discounts as discounts",
                "in.amount_paid as amount_paid",
            )
            ->get();
    
        return response()->json(['message' => 'List of Consultation', 'data' => $data]);
    }
    

    public function indexPublic()
    {
        $consultationList = Consultation::all();

        return response()->json(['message' => 'List of Consultation', 'data' => $consultationList]);
    }

    public function show(string $id)
    {
        $data = DB::table("consultations as c")
            ->join("consultation_types as tc", "tc.id", "c.consultation_type_id")
            ->join("doctors as d", "d.id", "c.doctor_id")
            ->join("persons as p", "p.id", "d.person_id")
            ->join("patients as pc", "pc.id", "c.pacient_id")
            ->join("persons as ps", "ps.id", "pc.person_id")
            ->join("users as us", "us.id", "ps.user_id")
            ->join("invoices as in", "c.id", "in.consultation_id")
            ->select(
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as doctor"),
                DB::raw("CONCAT(ps.first_name, ' ', ps.last_name) as paciente"),
                "c.id",                
                "c.date as fecha",
                "c.hour as hora",
                "c.observation as observacion",
                "c.status as estado",
                "tc.name as tipo_consulta",
                "ps.address as direccion",
                "us.email as correo",
                "in.invoice_number as invoice_number",
                "in.start_date as start_date",
                "in.due_date as due_date",
                "in.status as status",
                "in.total_amount as total_amount",
                "in.taxes as taxes",
                "in.discounts as discounts",
                "in.amount_paid as amount_paid",
            )
            ->get();
    
        return response()->json(['message' => 'Consultation Found in Show', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'observation' => 'required|string',
            'status' => 'required|string',
            'date' => 'required',
            'hour' => 'required|string',
            'consultation_type_id' => 'required|int',
            'doctor_id' => 'required|int',
            'pacient_id' => 'required|int'
        ]);
        
        try {
        
            $consultation = new Consultation();
            $consultation->observation = $request->observation;
            $consultation->status = $request->status;
            $consultation->date = $request->date; 
            $consultation->hour = $request->hour;  
            $consultation->consultation_type_id = $request->consultation_type_id;
            $consultation->doctor_id = $request->doctor_id;
            $consultation->pacient_id = $request->pacient_id;          
            $consultation->created_by_user = Auth::id();
            $consultation->updated_by_user = Auth::id();
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
            'hour' => 'required',
            'consultation_type_id' => 'required|int',
            'doctor_id' => 'required|int',
            'pacient_id' => 'required|int'
        ]);

        try {
            $consultation = Consultation::find($id);
            $consultation->observation = $request->observation;
            $consultation->status = $request->status;
            $consultation->date = $request->date; 
            $consultation->hour = $request->hour;   
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
