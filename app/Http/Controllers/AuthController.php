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

class AuthController extends Controller
{
    public function register(Request $request)
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
            // 'password_confirmation' => 'required|string|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->rol = "paciente";
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->save();

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
                "user_id" => $user->id
            ]);
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
            return response()->json(['message' => $th->getMessage()],422);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);


        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->route('login')
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => __('auth.failed')
                ]);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'Login exitoso']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión creada correctamente']);
    }
}
