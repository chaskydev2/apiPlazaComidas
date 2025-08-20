<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $idempresa = $request->input('idempresa');
        $sortCol   = $request->input('sortBy.sort', 'id');
        $sortDir   = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit     = (int) $request->input('limit', 10);
        $page      = (int) $request->input('page', 1);

        // columnas permitidas
        $sortable = ['id','name','price','is_available','created_at','updated_at'];
        if (! in_array($sortCol, $sortable, true)) {
            $sortCol = 'id';
        }

        $query = Product::with('empresa');

        // Filtro por empresa
        if (! empty($idempresa)) {
            $query->where('idempresa', $idempresa);
        }

        // Búsqueda
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
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
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'image_url'    => 'nullable|string|max:255', // permitimos URL o ruta local
            'is_available' => 'nullable|boolean',
            'price'        => 'required|numeric|min:0',
            'idempresa'    => 'required|integer|exists:empresa,idempresa',
        ], [
            'name.required'        => 'El nombre es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'price.required'       => 'El precio es obligatorio.',
            'price.numeric'        => 'El precio debe ser numérico.',
            'idempresa.required'   => 'La empresa es obligatoria.',
            'idempresa.exists'     => 'La empresa seleccionada no existe.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el producto.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $data = $v->validated();
        $data['is_available'] = $data['is_available'] ?? true;

        $product = Product::create($data);

        return response()->json($product->load('empresa'), 201);
    }

    public function show(string $id)
    {
        $product = Product::with('empresa')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $v = Validator::make($request->all(), [
            'name'         => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'image_url'    => 'sometimes|nullable|string|max:255',
            'is_available' => 'sometimes|boolean',
            'price'        => 'sometimes|numeric|min:0',
            'idempresa'    => 'sometimes|integer|exists:empresa,idempresa',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el producto.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $product->update($v->validated());

        return response()->json($product->load('empresa'));
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado.']);
    }
}
