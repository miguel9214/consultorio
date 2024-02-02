<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\Pacient;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function doctor()
    {
        $data = DB::table("doctors as d")
            ->join("persons as p", "p.id", "d.person_id")
            ->select(
                "d.id",
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as doctor"),
            )->get();
    
        return response()->json(['message' => 'List of Persons', 'data' => $data]);
    }

    public function pacient()
    {
        $data = DB::table("patients as pt")
            ->join("persons as p", "p.id", "pt.person_id")
            ->join("users as us", "us.id", "p.user_id")
            ->select(
                "pt.id",
                "us.email as correo",
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient"),
            )->get();
    
        return response()->json(['message' => 'List of Persons', 'data' => $data]);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'type_document' => 'required|string',
            'document' => 'required|unique:persons,document,NULL,id,type_document,' . $request->type_document,
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
            $user->rol = "paciente";
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
            $person->save();

            $patients = new Pacient();
            $patients->affilliate_type = $request->affilliate_type;
            $patients->eps_id = $request->eps_id;
            $patients->person_id = $person->id;
            $patients->save();

            DB::commit();
            return response()->json(['message' => 'Users created successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);


        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function userProfile()
    {
        try {
            return response()->json(auth('api')->user());
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
                'access_token' => $token,
                'user'=>auth('api')->user(),
        ]);
    }
}
