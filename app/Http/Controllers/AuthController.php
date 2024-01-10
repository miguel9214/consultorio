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

        // Inicio de la Transacción (Db::beginTransaction): Se inicia una transacción antes de realizar cualquier operación que forme parte de ella.

        // Operaciones en la Base de Datos: Se realizan varias operaciones de lectura o escritura en la base de datos como parte de la transacción.
        
        // **Confirmación de la Transacción (Db::commit): Si todas las operaciones se han completado con éxito, se utiliza la función Db::commit` para confirmar la transacción. Esto significa que todas las operaciones realizadas en la transacción se guardan permanentemente en la base de datos.
        
        // **Rollback de la Transacción (Db::rollBack): Si algo sale mal en cualquier punto de la transacción, puedes utilizar la función Db::rollBack` para deshacer todas las operaciones realizadas desde el inicio de la transacción, llevando la base de datos de nuevo a su estado original.


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
