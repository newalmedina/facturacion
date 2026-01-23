<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    // LOGIN
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Credenciales incorrectas'
                ]);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'code' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'message' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => e->errors()
            ]);
        }
    }

    // INFORMACIÓN DEL USUARIO
    public function userInformation(Request $request)
    {
        try {
            return response()->json([
                'user' => $request->user()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Ocurrió un error al obtener la información del usuario'
            ], 500);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Ocurrió un error al cerrar la sesión'
            ], 500);
        }
    }
}
