<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MesaController extends Controller
{
    /**
     * Listado con paginación, búsqueda, orden y filtros.
     * Query params:
     * - search: string (busca por numeromesa y qr)
     * - idempresa: int (filtra por empresa)
     * - sortBy[sort]: columna (idmesa, numeromesa, created_at, updated_at)
     * - sortBy[order]: asc|desc
     * - limit: int (default 10)
     * - page: int  (default 1)
     */
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $idempresa = $request->input('idempresa');
        $sortCol   = $request->input('sortBy.sort', 'idmesa');
        $sortDir   = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit     = (int) $request->input('limit', 10);
        $page      = (int) $request->input('page', 1);

        $sortable = ['idmesa', 'numeromesa', 'created_at', 'updated_at'];
        if (!in_array($sortCol, $sortable, true)) {
            $sortCol = 'idmesa';
        }

        $query = Mesa::with('empresa');

        // Filtro por empresa
        if (!empty($idempresa)) {
            $query->where('idempresa', $idempresa);
        }

        // Búsqueda por número de mesa o por QR
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('numeromesa', (int) $search);
                } else {
                    $q->where('qr', 'like', "%{$search}%");
                }
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
                'idempresa'    => $idempresa,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'idempresa'  => 'required|integer|exists:empresa,idempresa',
            'numeromesa' => [
                'required',
                'integer',
                'min:1',
                // Unicidad por empresa (evita duplicar el número de mesa en la misma empresa)
                Rule::unique('mesa', 'numeromesa')->where(function ($q) use ($request) {
                    return $q->where('idempresa', $request->input('idempresa'));
                }),
            ],
            'qr' => 'nullable|string|max:255',
        ], [
            'idempresa.required'  => 'La empresa es obligatoria.',
            'idempresa.integer'   => 'El id de empresa debe ser numérico.',
            'idempresa.exists'    => 'La empresa seleccionada no existe.',
            'numeromesa.required' => 'El número de mesa es obligatorio.',
            'numeromesa.integer'  => 'El número de mesa debe ser numérico.',
            'numeromesa.min'      => 'El número de mesa debe ser al menos 1.',
            'numeromesa.unique'   => 'Ya existe una mesa con ese número en esta empresa.',
            'qr.max'              => 'El QR no puede exceder 255 caracteres.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la mesa.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $mesa = Mesa::create($v->validated());

        return response()->json($mesa->load('empresa'), 201);
    }

    public function show(string $id)
    {
        $mesa = Mesa::with('empresa')->findOrFail($id);
        return response()->json($mesa);
    }

    public function update(Request $request, string $id)
    {
        $mesa = Mesa::findOrFail($id);

        $v = Validator::make($request->all(), [
            'idempresa'  => 'sometimes|integer|exists:empresa,idempresa',
            'numeromesa' => [
                'sometimes',
                'integer',
                'min:1',
                // mantener unicidad por empresa, considerando cambios de idempresa y/o numeromesa
                Rule::unique('mesa', 'numeromesa')
                    ->ignore($mesa->idmesa, 'idmesa')
                    ->where(function ($q) use ($request, $mesa) {
                        $empresaId = $request->input('idempresa', $mesa->idempresa);
                        return $q->where('idempresa', $empresaId);
                    }),
            ],
            'qr' => 'sometimes|nullable|string|max:255',
        ], [
            'idempresa.integer'   => 'El id de empresa debe ser numérico.',
            'idempresa.exists'    => 'La empresa seleccionada no existe.',
            'numeromesa.integer'  => 'El número de mesa debe ser numérico.',
            'numeromesa.min'      => 'El número de mesa debe ser al menos 1.',
            'numeromesa.unique'   => 'Ya existe una mesa con ese número en esta empresa.',
            'qr.max'              => 'El QR no puede exceder 255 caracteres.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la mesa.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $mesa->update($v->validated());

        return response()->json($mesa->load('empresa'));
    }

    public function destroy(string $id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();

        return response()->json(['message' => 'Mesa eliminada.']);
    }
}
