<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * List all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return response()->json(['data' => $users]);
    }

    /**
     * Register a new user and return a token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
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

        $user = User::create([
            'usuario'   => $data['usuario'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'idempresa' => $data['idempresa'] ?? null,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data'    => $user,
            'token'   => $token
        ], 201);
    }

    /**
     * Show a user by idusuario.
     *
     * @param string $idusuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $idusuario): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($idusuario);
        return response()->json(['data' => $user]);
    }

    /**
     * Update a user by idusuario.
     *
     * @param Request $request
     * @param string $idusuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $idusuario): \Illuminate\Http\JsonResponse
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
     * Delete a user by idusuario.
     *
     * @param string $idusuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $idusuario): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($idusuario);
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente.'
        ]);
    }
}