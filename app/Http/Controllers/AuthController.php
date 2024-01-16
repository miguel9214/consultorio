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
    public function register(Request $request)
    {
        $request->validate([
            'type_document' => 'required|string',
            'document' => 'required|unique:persons,document,NULL,id,type_document,' .$request->type_document,
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
            // 'password_confirmation' => 'required|string|confirmed',
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
            return response()->json(['message' => 'Usuario created successfully']);
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


        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json(['message' => 'correo o contraseña incorrectos'],422);
        }

        $token = JWTAuth::fromUser(Auth::user());

        return response()->json(['message' => 'Login exitoso','accessToken'=>$token]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión creada correctamente']);
    }
}
