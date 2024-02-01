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
            'invoice_number' => 'required|string',
            'due_date' => 'required|string',
            'total_amount' => 'required|string',
            'taxes' => 'required|string',
            'discounts' => 'required|date',
            'amount_paid' => 'required|date',
        ]);

        try {
            $invoices = new Invoices();
            $invoices->invoice_number = $request->invoice_number;
            $invoices->due_date = $request->due_date;
            $invoices->total_amount = $request->total_amount;
            $invoices->taxes = $request->taxes;
            $invoices->discounts = $request->discounts;
            $invoices->amount_paid = $request->amount_paid;
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
