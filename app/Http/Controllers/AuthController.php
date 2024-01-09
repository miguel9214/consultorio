<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'rol' => 'required|string',
            'name' => 'requerid|string',
            'email' => 'required|email|string',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
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

        $user = new User();
        $user->rol = $request->rol;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->type_document = $request->type_document;
        $user->document = $request->document;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->sex = $request->sex;
        $user->phone = $request->phone;
        $user->birthdate = $request->birthdate;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->neighborhood = $request->neighborhood;
        $user->created_by_user = $request->created_by_user;
        $user->updated_by_user = $request->updated_by_user;
        $user->save();

        if ($user) {
            return response()->json(['message' => 'Usuario created successfully']);
        } else {
            return response()->json(['message' => 'Error']);
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

        return redirect()->intended('/')->with('status', 'You are logged in');
    }

    public function userProfile(Request $request)
    {
        return response()->json([
            "message" => "userProfile ok",
            "userData" => auth()->user()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/login");
    }

    public function allUsers()
    {
        $users = User::all();

        return response()->json([
            "users" => $users
        ]);
    }
}
