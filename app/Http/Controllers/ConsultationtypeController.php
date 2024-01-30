<?php

namespace App\Http\Controllers;

use App\Models\Consultationtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ConsultationtypeController extends Controller
{
    public function index()
    {

        $ConsultationTypeList = Consultationtype::all();
        
        return response()->json(['message' => 'List of Consultation Type', 'data' => $ConsultationTypeList]);
    }

    public function indexPublic()
    {
        $ConsultationTypeList = Consultationtype::all();

        return response()->json(['message' => 'List of Consultation Type', 'data' => $ConsultationTypeList]);
    }

    public function show(string $id)
    {
        $ConsultationType = Consultationtype::find($id);

        if ($ConsultationType) {
            return response()->json(['message' => 'Consultation Type found', 'data' => $ConsultationType]);
        } else {
            return response()->json(['message' => 'Consultation Type not found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            $ConsultationType = new Consultationtype();
            $ConsultationType->name = $request->name;
            $ConsultationType->price = $request->price;
            $ConsultationType->save();

            return response()->json(['message' => 'Consultation Type created successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating Consultation Type: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            $ConsultationType = Consultationtype::find($id);
            $ConsultationType->name = $request->name;
            $ConsultationType->price = $request->price;
            $ConsultationType->save();

            return response()->json(['message' => 'Consultation Type updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating Consultation Type: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $ConsultationType = Consultationtype::find($id);

        if (!$ConsultationType) {
            return response()->json(['message' => 'Consultation Type not delete']);
        }

        $ConsultationType->delete();

        return response()->json(['message' => 'Consultation Type deleted successfully']);
    }
}
