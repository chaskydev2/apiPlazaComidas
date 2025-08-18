<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Login de usuario y devuelve token + datos de usuario.
     */
    public function login(Request $request)
    {
       
    
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar por email o por nombre de usuario
        $user = User::where('email', $credentials['usuario'])
            ->orWhere('usuario', $credentials['usuario'])
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado. Por favor ingrese datos válidos.'], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
