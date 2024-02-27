<?php

namespace App\Http\Controllers;

use App\Models\ConsultationType;
use App\Models\Medico;
use App\Models\Pacient;
use App\Models\Consultation;
use App\Models\Invoices;
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
            ->select(
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as doctor"),
                DB::raw("CONCAT(ps.first_name, ' ', ps.last_name) as paciente"),
                "c.id",
                "tc.name as tipo_consulta",
                "c.date as fecha",
                "c.hour as hora",
                "c.observation as observacion",
                "c.status as estado",
            )
            ->get();

        return response()->json(['message' => 'List of Consultation', 'data' => $data]);
    }

    public function indexConsultationInvoice(string $id)
    {
        $data = DB::table("consultations as c")
            ->join("consultation_types as tc", "tc.id", "c.consultation_type_id")
            ->join("doctors as d", "d.id", "c.doctor_id")
            ->join("persons as p", "p.id", "d.person_id")
            ->join("patients as pc", "pc.id", "c.pacient_id")
            ->join("persons as ps", "ps.id", "pc.person_id")
            ->join("users as u", "u.id", "ps.user_id")
            ->where('c.id', $id)
            ->select(
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as doctor"),
                DB::raw("CONCAT(ps.first_name, ' ', ps.last_name) as pacient"),
                "c.id",
                "c.date as date_consult",
                "c.hour as hour",
                "c.observation as observation",
                "c.status as status_consult",
                "tc.name as type_consult",
                "tc.price as price",
                "ps.address as address",
                "ps.phone as phone",
                "u.email as email",
            )->first();

        $invoice = DB::table("invoices")->select(
            DB::raw("(IFNULL(MAX(invoice_number),0)+1) as next_invoice_number"),
        )->first();

        $data->next_invoice_number = !empty($invoice->next_invoice_number) ? $invoice->next_invoice_number : 1;

        if (!$data) {
            return response()->json(['error' => 'InvoiceConsultation not found'], 404);
        }

        return response()->json(['message' => 'InvoiceConsultation found', 'data' => $data]);
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
        $invoices = Invoices::find($id);

        if (!$invoices && !$consultation) {
            return response()->json(['message' => 'Consultation not delete']);
        }

        $consultation->delete();

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
