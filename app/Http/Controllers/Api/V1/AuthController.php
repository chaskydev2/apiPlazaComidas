<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Login de usuario y devuelve token + datos de usuario.
     */
    public function login(Request $request)
    {
        // Validaciones con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'usuario'  => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'usuario.required'  => 'El campo usuario o email es obligatorio.',
            'usuario.string'    => 'El usuario debe ser un texto válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el login.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $credentials = $validator->validated();

        // Buscar por email o por nombre de usuario
        $user = User::where('email', $credentials['usuario'])
            ->orWhere('usuario', $credentials['usuario'])
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado. Verifique sus datos e intente nuevamente.'
            ], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas. Intente nuevamente.'
            ], 401);
        }

        // Generar token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso.',
            'token'   => $token,
            'user'    => $user,
        ]);
    }
}
