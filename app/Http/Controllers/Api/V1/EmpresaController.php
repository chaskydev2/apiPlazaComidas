<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    /**
     * Listado con paginación, búsqueda, orden y filtro por idcategoria.
     * Query params:
     * - search: string
     * - idcategoria: int (filtra por categoría)
     * - sortBy[sort]: columna (idempresa, name, normalized_name, stars, created_at, updated_at)
     * - sortBy[order]: asc|desc
     * - limit: int (por página)
     * - page: int (página)
     */
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $idcategoria = $request->input('idcategoria');
        $sortCol     = $request->input('sortBy.sort', 'idempresa');
        $sortDir     = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit       = (int) $request->input('limit', 10);
        $page        = (int) $request->input('page', 1);

        $sortable = ['idempresa','name','normalized_name','stars','created_at','updated_at'];
        if (! in_array($sortCol, $sortable, true)) {
            $sortCol = 'idempresa';
        }

        $query = Empresa::query()
            ->with('categoria'); // para ver la categoría relacionada

        // Filtro por idcategoria
        if (! empty($idcategoria)) {
            $query->where('idcategoria', $idcategoria);
        }

        // Búsqueda multi-campo
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
                'idcategoria'  => $idcategoria,
            ],
        ]);
    }

    public function store(Request $request)
    {
        // NOTA: ahora usamos idcategoria (FK real), no idCategoriaFood.
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'normalizedName'  => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'googleMapsUrl'   => 'nullable|string|max:255',
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

            'idcategoria'     => 'required|integer|exists:categoria,idcategoria', // ✅ FK real
        ], [
            'idcategoria.required' => 'La categoría es obligatoria.',
            'idcategoria.integer'  => 'La categoría debe ser numérica.',
            'idcategoria.exists'   => 'La categoría seleccionada no existe.',
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
            'image_url'         => $v['imageUrl'] ?? null,
            'logo_url'          => $v['logoUrl'] ?? null,
            'is_especial'       => $v['is_especial'] ?? false,
            'location'          => $v['location'] ?? null,
            'manager_id'        => $v['managerId'] ?? null,
            'stars'             => $v['stars'] ?? null,
            'open_days'         => $v['openDays'] ?? null,
            'open_hours'        => $v['openHours'] ?? null,
            'idcategoria'       => $v['idcategoria'], // ✅ FK
        ];

        $empresa = Empresa::create($payload);

        return response()->json([
            'message' => 'Empresa creada correctamente.',
            'data'    => $empresa->load('categoria'),
        ], 201);
    }

    public function show(string $id)
    {
        $empresa = Empresa::with('categoria')->findOrFail($id);
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

            'idcategoria'     => 'sometimes|integer|exists:categoria,idcategoria', // ✅ FK
        ], [
            'idcategoria.integer'  => 'La categoría debe ser numérica.',
            'idcategoria.exists'   => 'La categoría seleccionada no existe.',
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
            'imageUrl'        => 'image_url',
            'logoUrl'         => 'logo_url',
            'is_especial'     => 'is_especial',
            'location'        => 'location',
            'managerId'       => 'manager_id',
            'stars'           => 'stars',
            'openDays'        => 'open_days',
            'openHours'       => 'open_hours',
            'idcategoria'     => 'idcategoria', // ✅ FK
        ];

        $payload = [];
        foreach ($map as $in => $db) {
            if (array_key_exists($in, $v)) {
                $payload[$db] = $v[$in];
            }
        }

        if (array_key_exists('name', $v) && !array_key_exists('normalizedName', $v)) {
            $payload['normalized_name'] = mb_strtolower($v['name']);
        }

        $empresa->update($payload);

        return response()->json([
            'message' => 'Empresa actualizada correctamente.',
            'data'    => $empresa->load('categoria'),
        ]);
    }

    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return response()->json(['message' => 'Empresa eliminada.']);
    }
}
