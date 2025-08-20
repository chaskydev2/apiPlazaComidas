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
     * Listado con paginación, búsqueda, orden y filtros.
     * Query params:
     * - search: string (busca en usuario, email, role)
     * - role: cliente|manager|admin
     * - idempresa: int (filtra por empresa)
     * - sortBy[sort]: columna (idusuario, usuario, email, role, created_at, updated_at)
     * - sortBy[order]: asc|desc
     * - limit: int (por página, default 10)
     * - page: int (default 1)
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search    = $request->input('search');
        $role      = $request->input('role');
        $idempresa = $request->input('idempresa');
        $sortCol   = $request->input('sortBy.sort', 'idusuario');
        $sortDir   = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit     = (int) $request->input('limit', 10);
        $page      = (int) $request->input('page', 1);

        $sortable = ['idusuario', 'usuario', 'email', 'role', 'created_at', 'updated_at'];
        if (!in_array($sortCol, $sortable, true)) {
            $sortCol = 'idusuario';
        }

        $query = User::with('empresa');

        // Filtro por role
        if (!empty($role)) {
            $query->where('role', $role);
        }

        // Filtro por empresa
        if (!empty($idempresa)) {
            $query->where('idempresa', $idempresa);
        }

        // Búsqueda
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('usuario', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Orden
        $query->orderBy($sortCol, $sortDir);

        // Paginación
        $result = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->items(),
            'meta' => [
                'current_page' => $result->currentPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
                'last_page'    => $result->lastPage(),
                'sort_by'      => $sortCol,
                'sort_dir'     => $sortDir,
                'search'       => $search,
                'role'         => $role,
                'idempresa'    => $idempresa,
            ],
        ]);
    }

    /**
     * Register a new user and return a token.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'usuario'   => 'required|string|max:255|unique:users,usuario',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8',
            'idempresa' => 'nullable|integer|exists:empresa,idempresa',
            'role'      => 'nullable|string|in:cliente,manager,admin',
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
            'role.in'           => 'El rol debe ser cliente, manager o admin.',
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
            'role'      => $data['role'] ?? 'cliente',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data'    => $user->load('empresa'),
            'token'   => $token
        ], 201);
    }

    /**
     * Show a user by idusuario.
     */
    public function show(string $idusuario): \Illuminate\Http\JsonResponse
    {
        $user = User::with('empresa')->findOrFail($idusuario);
        return response()->json(['data' => $user]);
    }

    /**
     * Update a user by idusuario.
     */
    public function update(Request $request, string $idusuario): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($idusuario);

        $validator = Validator::make($request->all(), [
            'usuario'   => 'sometimes|string|max:255|unique:users,usuario,' . $idusuario . ',idusuario',
            'email'     => 'sometimes|string|email|max:255|unique:users,email,' . $idusuario . ',idusuario',
            'password'  => 'nullable|string|min:8',
            'idempresa' => 'nullable|integer|exists:empresa,idempresa',
            'role'      => 'sometimes|string|in:cliente,manager,admin',
        ], [
            'usuario.string'    => 'El usuario debe ser un texto válido.',
            'usuario.unique'    => 'Este nombre de usuario ya está en uso.',
            'email.email'       => 'El email debe tener un formato válido.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'idempresa.integer' => 'El id de empresa debe ser un número.',
            'idempresa.exists'  => 'La empresa seleccionada no existe.',
            'role.in'           => 'El rol debe ser cliente, manager o admin.',
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
            'data'    => $user->load('empresa')
        ]);
    }

    /**
     * Delete a user by idusuario.
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
