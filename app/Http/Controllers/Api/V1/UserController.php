<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa tu modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para el hash de la contraseña

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Puedes agregar lógica para seleccionar solo ciertos campos o paginar
        return response()->json(User::all());
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario' => 'required|string|max:255|unique:users,usuario', // 'usuario' en lugar de 'name'
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8', // Las contraseñas deben ser de al menos 8 caracteres
            'idempresa' => 'nullable|integer|exists:empresas,idempresa', // Asumiendo que 'empresas' es el nombre de la tabla
        ]);

        // Hashea la contraseña antes de guardar si no usas el mutador en el modelo para el 'set'
        // Si ya tienes el mutador `password(): Attribute` en tu modelo User, puedes omitir esta línea
        // Laravel 10+ con el mutador maneja el hashing automáticamente al asignar la 'password'.
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        // Opcional: Cargar la relación 'empresa' si es relevante para la respuesta
        // $user->load('empresa');

        return response()->json($user, 201); // 201 Created
    }

    /**
     * Display the specified user.
     *
     * @param  string  $idusuario // Utiliza el nombre de tu clave primaria
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $idusuario)
    {
        $user = User::findOrFail($idusuario);
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $idusuario // Utiliza el nombre de tu clave primaria
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $idusuario)
    {
        $user = User::findOrFail($idusuario);

        $data = $request->validate([
            'usuario' => 'string|max:255|unique:users,usuario,' . $idusuario . ',idusuario', // Ignora el propio usuario en la validación unique
            'email' => 'string|email|max:255|unique:users,email,' . $idusuario . ',idusuario',
            'password' => 'nullable|string|min:8', // La contraseña es opcional al actualizar
            'idempresa' => 'nullable|integer|exists:empresas,idempresa',
        ]);

        // Hashear la nueva contraseña si se proporciona
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Opcional: Cargar la relación 'empresa' si es relevante para la respuesta
        // $user->load('empresa');

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  string  $idusuario // Utiliza el nombre de tu clave primaria
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $idusuario)
    {
        $user = User::findOrFail($idusuario);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado exitosamente.']);
    }
}