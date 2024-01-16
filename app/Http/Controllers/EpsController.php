<?php

namespace App\Http\Controllers;

use App\Models\Eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class EpsController extends Controller
{
    public function index()
    {
        $epsList = Eps::all();

        return response()->json(['message' => 'List of EPS', 'data' => $epsList]);
    }

    public function indexPublic()
    {
        $epsList = Eps::all();

        return response()->json(['message' => 'List of EPS', 'data' => $epsList]);
    }

    public function show(string $id)
    {
        $eps = Eps::find($id);

        if ($eps) {
            return response()->json(['message' => 'EPS found', 'data' => $eps]);
        } else {
            return response()->json(['message' => 'EPS not found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'code' => 'required|string',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date',
        ]);

        try {
            $eps = new Eps();
            $eps->name = $request->name;
            $eps->address = $request->address;
            $eps->phone = $request->phone;
            $eps->code = $request->code;
            $eps->contract_start_date = $request->contract_start_date;
            $eps->contract_end_date = $request->contract_end_date;
            $eps->save();

            return response()->json(['message' => 'EPS created successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating EPS: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'code' => 'required|string',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date',
        ]);

        try {
            $eps = Eps::find($id);
            $eps->name = $request->name;
            $eps->address = $request->address;
            $eps->phone = $request->phone;
            $eps->code = $request->code;
            $eps->contract_start_date = $request->contract_start_date;
            $eps->contract_end_date = $request->contract_end_date;
            $eps->save();

            return response()->json(['message' => 'EPS update successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating EPS: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return response()->json(['message' => 'EPS not found']);
        }

        $eps->delete();

        return response()->json(['message' => 'EPS deleted successfully']);
    }
}
