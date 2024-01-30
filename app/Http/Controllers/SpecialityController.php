<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialityList = Specialty::all();

        return response()->json(['message' => 'List of speciallty', 'data' => $specialityList]);
    }

    public function indexPublic()
    {
        $specialityList = Specialty::all();

        return response()->json(['message' => 'List of speciallty', 'data' => $specialityList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $speciality = new Specialty();
            $speciality->name = $request->name;
            $speciality->save();
            DB::commit();
            return response()->json(['message' => 'Speciality created successfully']);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating speciality: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $speciality = Specialty::find($id);

        if ($speciality) {
            return response()->json(['message' => 'Speciality found', 'data' => $speciality]);
        } else {
            return response()->json(['message' => 'Speciality not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $speciality=Specialty::find($id);
            $speciality->name = $request->name;
            $speciality->save();
            DB::commit();
            return response()->json(['message' => 'Speciality updated successfully']);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating speciality: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $speciality = Specialty::find($id);

        if (!$speciality) {
            return response()->json(['message' => 'Speciality not delete']);
        }

        $speciality->delete();

        return response()->json(['message' => 'Speciality deleted successfully']);
    }
}
