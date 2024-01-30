<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class InvoiceController extends Controller
{
    public function index()
    {

        $invoicesList = Invoices::all(); 

        return response()->json(['message' => 'List of Invoices', 'data' => $invoicesList]);
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
