<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = Person::all();

        return response()->json(['message' => 'List person', 'data' => $persons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_document' => 'required|string',
            'document' => 'required|numeric',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'sex' => 'required|string',
            'phone' => 'required|numeric',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'neighborhood' => 'required|string',
            'created_by_user' => 'required|numeric',
            'updated_by_user' => 'required|numeric'

        ]);

        $person = Person::create([
            'type_document' => $request->input('type_document'),
            'document' => $request->input('document'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'neighborhood' => $request->input('neighborhood'),
            'created_by_user' => $request->input('created_by_user'),
            'updated_by_user' => $request->input('updated_by_user')
        ]);

        if ($person) {
            return response()->json(['message' => 'Person created successfully']);
        } else {
            return response()->json(['message' => 'Error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
