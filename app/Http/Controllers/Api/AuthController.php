<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'Usuario registrado exitosamente. Por favor, verifica tu email.'], 201);
    }

    public function login(Request $request)
    {
        // Validar los datos del login con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }
}