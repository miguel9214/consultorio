<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index()
    {
        $data = DB::table("invoices as in")
            ->join("consultations as c", "c.id", "in.consultation_id")
            ->join("patients as pts", "pts.id", "c.pacient_id")
            ->join("persons as p", "p.id", "pts.person_id")
            ->join("users as us", "us.id", "p.user_id")
            ->select(
                "in.id",
                "in.invoice_number as factura",
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as paciente"),
                "us.email as correo",
                "in.start_date as fecha",
                "in.amount_paid as pagar",
                "in.status as estado"
            )->get();

        return response()->json(['message' => 'List of Invoices', 'data' => $data]);
    }

    public function indexConsultationInvoice(string $id)
    {
        $data = DB::table("invoices as in")
            ->join("consultations as c", "c.id", "in.consultation_id")
            ->join("consultation_types as tc", "tc.id", "c.consultation_type_id")
            ->join("patients as pts", "pts.id", "c.pacient_id")
            ->join("persons as p", "p.id", "pts.person_id")
            ->join("users as us", "us.id", "p.user_id")
            ->where('in.id', $id)
            ->select(
                DB::raw("CONCAT(ps.first_name, ' ', ps.last_name) as pacient"),
                "in.id",
                "in.invoice_number as invoice_number",
                "in.start_date as start_date",
                "in.due_date as due_date",
                "in.status as status",
                "in.total_amount as total_amount",
                "in.taxes as taxes",
                "in.discounts as discounts",
                "in.amount_paid as amount_paid",
                "in.consultation_id as consultation_id",
                "c.id",
                "c.observation as observation",
                "c.status as status_consult",
                "c.hour as hour",
                "c.date as date_consult",
                "tc.name as type_consult",
                "tc.price as price",
                "ps.address as address",
                "ps.phone as phone",
                "u.email as email",
            )->first();

        if (!$data) {
            return response()->json(['error' => 'Invoice_Consultation not found'], 404);
        }

        return response()->json(['message' => 'Invoice_Consultation found', 'data' => $data]);
    }

    public function indexPublic()
    {
        $invoicesList = Invoices::all();

        return response()->json(['message' => 'List of Invoices', 'data' => $invoicesList]);
    }

    public function show(string $id)
    {
        $invoices = Invoices::find($id);

        if ($invoices) {
            return response()->json(['message' => 'Invoices found', 'data' => $invoices]);
        } else {
            return response()->json(['message' => 'Invoices not found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'total_amount' => 'required',
            'taxes' => 'required|numeric',
            'discounts' => 'required|',
            'amount_paid' => 'required',
            'consultation_id' => 'required',
        ]);

        try {
            $invoices = new Invoices();
            $invoices->invoice_number = $request->invoice_number;
            $invoices->start_date = $request->start_date;
            $invoices->due_date = $request->due_date;
            $invoices->status = $request->status;
            $invoices->total_amount = $request->total_amount;
            $invoices->taxes = $request->taxes;
            $invoices->discounts = $request->discounts;
            $invoices->amount_paid = $request->amount_paid;
            $invoices->consultation_id = $request->consultation_id;
            $invoices->save();

            return response()->json(['message' => 'Invoices created successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating Invoices: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'due_date' => 'required|string',
            'total_amount' => 'required|string',
            'taxes' => 'required|string',
            'discounts' => 'required|date',
            'amount_paid' => 'required|date',
        ]);

        try {
            $invoices = Invoices::find($id);
            $invoices->invoice_number = $request->invoice_number;
            $invoices->due_date = $request->due_date;
            $invoices->total_amount = $request->total_amount;
            $invoices->taxes = $request->taxes;
            $invoices->discounts = $request->discounts;
            $invoices->amount_paid = $request->amount_paid;
            $invoices->save();

            return response()->json(['message' => 'Invoices updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating Invoices: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $invoices = Invoices::find($id);

        if (!$invoices) {
            return response()->json(['message' => 'Invoices not delete']);
        }

        $invoices->delete();

        return response()->json(['message' => 'Invoices deleted successfully']);
    }
}
