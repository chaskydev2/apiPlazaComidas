<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    /**
     * Listado con paginación, búsqueda y orden.
     * Query params:
     * - search: string
     * - sortBy[sort]: columna (idempresa, name, normalized_name, stars, created_at, updated_at)
     * - sortBy[order]: asc|desc
     * - limit: int (por página)
     * - page: int (página)
     */
    public function index(Request $request)
    {
        // Leer parámetros al estilo de tu ejemplo
        $search  = $request->input('search');
        $sortCol = $request->input('sortBy.sort', 'idempresa');
        $sortDir = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit   = (int) $request->input('limit', 10);
        $page    = (int) $request->input('page', 1);

        // Columnas permitidas para ordenar
        $sortable = ['idempresa','name','normalized_name','stars','created_at','updated_at'];
        if (! in_array($sortCol, $sortable, true)) {
            $sortCol = 'idempresa';
        }

        $query = Empresa::query();

        // Búsqueda (multi-campo)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('normalized_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('google_maps_url', 'like', "%{$search}%");
            });
        }

        // Orden
        $query->orderBy($sortCol, $sortDir);

        // Paginación (mismo patrón que tu ejemplo)
        $result = $query->paginate($limit, ['*'], 'page', $page);

        // Respuesta uniforme (data + meta)
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
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Validaciones (inline, sin nuevos FormRequests)
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'normalizedName'  => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'googleMapsUrl'   => 'nullable|string|max:255',
            'idCategoriaFood' => 'nullable|string|max:255',
            'imageUrl'        => 'nullable|string|max:255',
            'logoUrl'         => 'nullable|string|max:255',
            'is_especial'     => 'nullable|boolean',
            'location'        => 'nullable|string|max:255',
            'managerId'       => 'nullable|string|max:255',
            'stars'           => 'nullable|integer|min:0|max:10',
            'openDays'        => 'nullable|array',
            'openDays.*'      => 'string',
            'openHours'       => 'nullable|array',
            'openHours.opening' => 'nullable|string|max:50',
            'openHours.closing' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la empresa.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $v = $validator->validated();

        $payload = [
            'name'              => $v['name'],
            'normalized_name'   => $v['normalizedName'] ?? mb_strtolower($v['name']),
            'description'       => $v['description'] ?? null,
            'google_maps_url'   => $v['googleMapsUrl'] ?? null,
            'id_categoria_food' => $v['idCategoriaFood'] ?? null,
            'image_url'         => $v['imageUrl'] ?? null,
            'logo_url'          => $v['logoUrl'] ?? null,
            'is_especial'       => $v['is_especial'] ?? false,
            'location'          => $v['location'] ?? null,
            'manager_id'        => $v['managerId'] ?? null,
            'stars'             => $v['stars'] ?? null,
            'open_days'         => $v['openDays'] ?? null,
            'open_hours'        => $v['openHours'] ?? null,
        ];

        $empresa = Empresa::create($payload);

        return response()->json([
            'message' => 'Empresa creada correctamente.',
            'data'    => $empresa,
        ], 201);
    }

    public function show(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return response()->json($empresa);
    }

    public function update(Request $request, string $id)
    {
        $empresa = Empresa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'            => 'sometimes|required|string|max:255',
            'normalizedName'  => 'sometimes|nullable|string|max:255',
            'description'     => 'sometimes|nullable|string',
            'googleMapsUrl'   => 'sometimes|nullable|string|max:255',
            'idCategoriaFood' => 'sometimes|nullable|string|max:255',
            'imageUrl'        => 'sometimes|nullable|string|max:255',
            'logoUrl'         => 'sometimes|nullable|string|max:255',
            'is_especial'     => 'sometimes|boolean',
            'location'        => 'sometimes|nullable|string|max:255',
            'managerId'       => 'sometimes|nullable|string|max:255',
            'stars'           => 'sometimes|nullable|integer|min:0|max:10',
            'openDays'        => 'sometimes|nullable|array',
            'openDays.*'      => 'string',
            'openHours'       => 'sometimes|nullable|array',
            'openHours.opening' => 'nullable|string|max:50',
            'openHours.closing' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la empresa.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $v = $validator->validated();

        $map = [
            'name'            => 'name',
            'normalizedName'  => 'normalized_name',
            'description'     => 'description',
            'googleMapsUrl'   => 'google_maps_url',
            'idCategoriaFood' => 'id_categoria_food',
            'imageUrl'        => 'image_url',
            'logoUrl'         => 'logo_url',
            'is_especial'     => 'is_especial',
            'location'        => 'location',
            'managerId'       => 'manager_id',
            'stars'           => 'stars',
            'openDays'        => 'open_days',
            'openHours'       => 'open_hours',
        ];

        $payload = [];
        foreach ($map as $in => $db) {
            if (array_key_exists($in, $v)) {
                $payload[$db] = $v[$in];
            }
        }

        // Si llega name y NO llega normalizedName, normalizamos automáticamente
        if (array_key_exists('name', $v) && !array_key_exists('normalizedName', $v)) {
            $payload['normalized_name'] = mb_strtolower($v['name']);
        }

        $empresa->update($payload);

        return response()->json([
            'message' => 'Empresa actualizada correctamente.',
            'data'    => $empresa,
        ]);
    }

    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return response()->json(['message' => 'Empresa eliminada.']);
    }
}
