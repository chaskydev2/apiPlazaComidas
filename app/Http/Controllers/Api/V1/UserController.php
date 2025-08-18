<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa tu modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para el hash de la contraseña
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validaciones con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'usuario'   => 'required|string|max:255|unique:users,usuario',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8',
            'idempresa' => 'nullable|integer|exists:empresa,idempresa',
        ], [
            'usuario.required'  => 'El campo usuario es obligatorio.',
            'usuario.string'    => 'El usuario debe ser un texto válido.',
            'usuario.unique'    => 'Este nombre de usuario ya está en uso.',
            'email.required'    => 'El campo email es obligatorio.',
            'email.email'       => 'El email debe tener un formato válido.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'idempresa.integer' => 'El id de empresa debe ser un número.',
            'idempresa.exists'  => 'La empresa seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el usuario.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']); // encriptamos la contraseña

        $user = new User();
        $user->usuario   = $data['usuario'];
        $user->email     = $data['email'];
        $user->password  = $data['password'];
        $user->idempresa = $data['idempresa'] ?? null;
        $user->save();

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data'    => $user
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(string $idusuario)
    {
        $user = User::findOrFail($idusuario);
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $idusuario)
    {
        $user = User::findOrFail($idusuario);

        $validator = Validator::make($request->all(), [
            'usuario'   => 'sometimes|string|max:255|unique:users,usuario,' . $idusuario . ',idusuario',
            'email'     => 'sometimes|string|email|max:255|unique:users,email,' . $idusuario . ',idusuario',
            'password'  => 'nullable|string|min:8',
            'idempresa' => 'nullable|integer|exists:empresa,idempresa',
        ], [
            'usuario.string'    => 'El usuario debe ser un texto válido.',
            'usuario.unique'    => 'Este nombre de usuario ya está en uso.',
            'email.email'       => 'El email debe tener un formato válido.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'idempresa.integer' => 'El id de empresa debe ser un número.',
            'idempresa.exists'  => 'La empresa seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el usuario.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data'    => $user
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $idusuario)
    {
        $user = User::findOrFail($idusuario);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado exitosamente.']);
    }
}