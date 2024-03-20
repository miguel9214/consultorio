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
            ->leftJoin("doctor_specialties as dspec", "ds.id", "dspec.doctor_id")
            ->leftJoin("specialties as sp", "dspec.speciality_id", "sp.id")
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
                "u.email as email",
                "sp.name as especialidad"  // Asegúrate de ajustar el nombre del campo de especialidad según tu esquema
            )
            ->orderBy('ds.id')  // Asegura que los resultados estén ordenados por el ID del doctor
            ->get();

        $formattedData = [];
        $currentDoctorId = null;
        foreach ($data as $row) {
            if ($row->id != $currentDoctorId) {
                $formattedData[] = [
                    'id' => $row->id,
                    'tipo_documento' => $row->tipo_documento,
                    'documento' => $row->documento,
                    'nombre' => $row->nombre,
                    'apellido' => $row->apellido,
                    'sexo' => $row->sexo,
                    'telefono' => $row->telefono,
                    'fecha_nacimiento' => $row->fecha_nacimiento,
                    'direccion' => $row->direccion,
                    'ciudad' => $row->ciudad,
                    'estado' => $row->estado,
                    'barrio' => $row->barrio,
                    'email' => $row->email,
                    'especialidades' => [$row->especialidad],
                ];
                $currentDoctorId = $row->id;
            } else {
                $formattedData[count($formattedData) - 1]['especialidades'][] = $row->especialidad;
            }
        }

        return response()->json(['message' => 'List of Doctors', 'data' => $formattedData]);
    }

    public function indexPublic()
    {
        $medicosList = Medico::all();

        return response()->json(['message' => 'List of Doctors', 'data' => $medicosList]);
    }

    public function show(string $id)
    {
        $medico = DB::table("persons as ps")
            ->join("users as u", "ps.user_id", "=", "u.id")
            ->join("doctors as ds", "ps.id", "=", "ds.person_id")
            ->leftJoin("doctor_specialties as dspec", "ds.id", "=", "dspec.doctor_id")
            ->leftJoin("specialties as sp", "dspec.speciality_id", "=", "sp.id")
            ->leftJoin("doctors as m", "ds.id", "=", "m.id") // Asegúrate de ajustar el nombre de la tabla 'doctors' según tu esquema
            ->where("m.id", $id) // Asegúrate de ajustar el nombre del campo 'id' según tu esquema
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
                "u.email as email",
                "sp.name as especialidad"  // Asegúrate de ajustar el nombre del campo de especialidad según tu esquema
            )
            ->orderBy('ds.id')  // Asegura que los resultados estén ordenados por el ID del doctor
            ->get();

        if ($medico->isEmpty()) {
            return response()->json(['message' => 'Doctor not found']);
        }

        $formattedData = [
            'id' => $medico[0]->id,
            'tipo_documento' => $medico[0]->tipo_documento,
            'documento' => $medico[0]->documento,
            'nombre' => $medico[0]->nombre,
            'apellido' => $medico[0]->apellido,
            'sexo' => $medico[0]->sexo,
            'telefono' => $medico[0]->telefono,
            'fecha_nacimiento' => $medico[0]->fecha_nacimiento,
            'direccion' => $medico[0]->direccion,
            'ciudad' => $medico[0]->ciudad,
            'estado' => $medico[0]->estado,
            'barrio' => $medico[0]->barrio,
            'email' => $medico[0]->email,
            'especialidades' => $medico->pluck('especialidad')->toArray(),
        ];

        return response()->json(['message' => 'Doctor found', 'data' => $formattedData]);
    }

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
            $medicos->person_id = $person->id;
            $medicos->save();


            foreach ($request->specialities as $speciality) {
                DB::table('doctor_specialties')->insert([
                    'doctor_id' => $medicos->id,
                    'speciality_id' => $speciality['id'],
                ]);
            }

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
            'sex' => 'required|string',
            'phone' => 'required|numeric',
            'birthdate' => 'required|date',
            'email' => 'required|email|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'neighborhood' => 'required|string',
            'especialidades' => 'required|array',
        ]);

        try {
            // Busca y actualiza el usuario
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->email = $request->email;
            $user->save();

            // Busca y actualiza la persona
            $person = Person::find($id);
            if (!$person) {
                return response()->json(['message' => 'Person not found'], 404);
            }
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
            $person->updated_by_user = Auth::id();
            $person->save();

            // Busca y actualiza al médico
            $medico = Medico::find($id);
            if (!$medico) {
                return response()->json(['message' => 'Medico not found'], 404);
            }
            // Si necesitas realizar cambios en el modelo Medico, hazlos aquí
            // $medico->campo = $request->campo;
            $medico->save();

            // Elimina todas las especialidades existentes para este médico
            DB::table('doctor_specialties')->where('doctor_id', $id)->delete();

            // Inserta las nuevas especialidades
            foreach ($request->especialidades as $especialidad) {
                DB::table('doctor_specialties')->insert([
                    'doctor_id' => $id,
                    'speciality_id' => $especialidad['id'],
                ]);
            }

            return response()->json(['message' => 'Doctor updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating Doctor: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Busca el médico
            $medico = Medico::find($id);

            if (!$medico) {
                // Si no se encuentra el médico, realiza el rollback y devuelve una respuesta
                DB::rollBack();
                return response()->json(['message' => 'Doctor not found aca'], 404);
            }

            // Obtiene el ID de la persona y el usuario asociado al médico
            $personId = $medico->person_id;
            $userId = Person::find($personId)->user_id;

            // Elimina las especialidades asociadas al médico
            DB::table('doctor_specialties')->where('doctor_id', $id)->delete();

            // Elimina al médico, la persona y el usuario
            $medico->delete();
            Person::find($personId)->delete();
            User::find($userId)->delete();

            // Commit después de eliminar correctamente
            DB::commit();

            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (QueryException $e) {
            // En caso de error, realiza el rollback y devuelve una respuesta con el mensaje de error
            DB::rollBack();
            return response()->json(['message' => 'Error deleting doctor: ' . $e->getMessage()], 500);
        }
    }
}
