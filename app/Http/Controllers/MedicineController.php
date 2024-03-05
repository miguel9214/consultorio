<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class MedicineController extends Controller
{
    public function index()
    {
        $medicine = Medicine::all();

        return response()->json(['message' => 'List medicine', 'data' => $medicine]);
}

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
        ]);

        $medicine = Medicine::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ]);
        $medicine->save();


        if ($medicine) {
            return response()->json(['message' => 'Medicine created successfully']);
        } else {
            return response()->json(['message' => 'Error in created the Medicine']);
        }
    }

    public function show(string $id)
    {
        $medicine = Medicine::find($id);

        if ($medicine) {
            return response()->json(['message' => 'Medicine found', 'data' => $medicine]);
        } else {
            return response()->json(['message' => 'Medicine not found']);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
        ]);

        try {
            $medicine = Medicine::find($id);
            $medicine->code = $request->code;
            $medicine->name = $request->name;
            $medicine->save();

            return response()->json(['message' => 'Medicine updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error in updated the Medicine: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {

            return response()->json(['message' => 'Medicine not found']);
        } else {

            $medicine->delete();

            return response()->json(['message' => 'Medicine deleted successfully']);
        }
    }
}
