<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Codification;
use Illuminate\Database\QueryException;


class MedicoController extends Controller
{
    public function index()
    {
        $data = DB::table("persons as ps")
            ->join("users as u", "ps.user_id", "u.id")
            ->join("doctors as ds", "ps.id", "ds.person_id")
            ->select(
                "ds.id as id",
                "ps.type_document as tipo_documento",
                "ps.document as documento",
                "ps.first_name as nombre",
                "ps.last_name as apellido",
                "ps.sex as sexo",
                "ps.phone as telefono",
                "ps.birthdate as fecha_nacimiento",
                "ps.address as direccion",
                "ps.city as ciudad",
                "ps.state as estado",
                "ps.neighborhood as barrio",
                "ds.speciality as especialidad",
                "u.email as email",
            )->get();


        return response()->json(['message' => 'List of Doctors', 'data' => $data]);
    }

    public function indexPublic()
    {
        $medicosList = Medico::all();

        return response()->json(['message' => 'List of Doctors', 'data' => $medicosList]);
    }

    public function show(string $id)
    {
        $medicos = Medico::find($id);

        if ($medicos) {
            return response()->json(['message' => 'Doctors found', 'data' => $medicos]);
        } else {
            return response()->json(['message' => 'Doctors not found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_document' => 'required|string',
            'document' => 'required|numeric',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'speciality' => 'required|string',
            'sex' => 'required|string',
            'phone' => 'required|numeric',
            'birthdate' => 'required|date',
            'email' => 'required|email|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'neighborhood' => 'required|string',
            'password' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->rol = "doctor";
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $person = new Person();
            $person->type_document = $request->type_document;
            $person->document = $request->document;
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            $person->sex = $request->sex;
            $person->phone = $request->phone;
            $person->birthdate = $request->birthdate;
            $person->address = $request->address;
            $person->city = $request->city;
            $person->state = $request->state;
            $person->neighborhood = $request->neighborhood;
            $person->user_id = $user->id;
            $person->created_by_user = Auth::id();
            $person->save();

            $medicos = new Medico();
            $medicos->speciality = $request->speciality;
            $medicos->person_id = $person->id;
            $medicos->save();

            DB::commit();
            return response()->json(['message' => 'Doctors created successfully']);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating Doctors: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'type_document' => 'required|string',
            'document' => 'required|numeric',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'speciality' => 'required|string',
            'sex' => 'required|string',
            'phone' => 'required|numeric',
            'birthdate' => 'required|date',
            'email' => 'required|email|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'neighborhood' => 'required|string',
            'password' => 'required',
        ]);

        try {
            $user = User::find($id);
            $user->email = $request->email;
            $user->save();

            $person = Person::find($id);
            $person->type_document = $request->type_document;
            $person->document = $request->document;
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            $person->sex = $request->sex;
            $person->phone = $request->phone;
            $person->birthdate = $request->birthdate;
            $person->address = $request->address;
            $person->city = $request->city;
            $person->state = $request->state;
            $person->neighborhood = $request->neighborhood;
            $person->updated_by_user = Auth::id(); //Auth::id() Es el id de la persona que esta en sesion
            $person->save();

            $medicos = Medico::find($id);
            $medicos->speciality = $request->speciality;
            $medicos->save();

            return response()->json(['message' => 'Doctors updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating Doctors: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $medicos = Medico::find($id);

        if (!$medicos) {
            return response()->json(['message' => 'Doctors not delete']);
        }

        $medicos->delete();

        return response()->json(['message' => 'Doctors deleted successfully']);
    }
}
