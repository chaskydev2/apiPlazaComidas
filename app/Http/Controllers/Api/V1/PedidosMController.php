<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PedidosM;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PedidosMController extends Controller
{
    /**
     * GET /api/v1/pedidos/select
     * ?search=...&idempresa=...&idusuario=...&status=...&sortBy[sort]=...&sortBy[order]=asc|desc&limit=10&page=1
     */
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $idempresa = $request->input('idempresa');
        $idusuario = $request->input('idusuario');
        $status    = $request->input('status');

        $sortCol = $request->input('sortBy.sort', 'idpedidosm');
        $sortDir = strtolower($request->input('sortBy.order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $limit   = (int) $request->input('limit', 10);
        $page    = (int) $request->input('page', 1);

        $sortable = ['idpedidosm','ordernumber','total','status','created_at','updated_at'];
        if (! in_array($sortCol, $sortable, true)) {
            $sortCol = 'idpedidosm';
        }

        $q = PedidosM::with(['empresa', 'usuario', 'items.product']);

        if (!empty($idempresa)) {
            $q->where('idempresa', $idempresa);
        }
        if (!empty($idusuario)) {
            $q->where('idusuario', $idusuario);
        }
        if (!empty($status)) {
            $q->where('status', $status);
        }
        if (!empty($search)) {
            $q->where(function ($w) use ($search) {
                $w->where('ordernumber', 'like', "%{$search}%")
                  ->orWhere('notas', 'like', "%{$search}%");
            });
        }

        $q->orderBy($sortCol, $sortDir);

        $result = $q->paginate($limit, ['*'], 'page', $page);

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
                'idusuario'    => $idusuario,
                'status'       => $status,
            ],
        ]);
    }

    /**
     * POST /api/v1/pedidos/create
     * Crea el pedido + items en una transacción.
     * Body esperado:
     * {
     *   "idempresa": 1,
     *   "idusuario": 1,
     *   "notas": "...",
     *   "status": "pending",
     *   "items": [
     *     {"idproducts": 10, "cantidad": 2, "precio": 15.5, "notas": "sin sal"},
     *     {"idproducts": 5,  "cantidad": 1, "precio": 30   }
     *   ]
     * }
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'idempresa' => 'required|integer|exists:empresa,idempresa',
            'idusuario' => 'required|integer|exists:users,idusuario',
            'notas'     => 'nullable|string',
            'status'    => 'nullable|string|max:50',

            'items'     => 'required|array|min:1',
            'items.*.idproducts' => 'required|integer|exists:products,id',
            'items.*.cantidad'   => 'required|integer|min:1',
            'items.*.precio'     => 'required|numeric|min:0',
            'items.*.notas'      => 'nullable|string',
        ], [
            'idempresa.required' => 'La empresa es obligatoria.',
            'idusuario.required' => 'El usuario es obligatorio.',
            'items.required'     => 'Debes enviar al menos un item.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el pedido.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $data = $v->validated();

        $pedido = DB::transaction(function () use ($data) {
            // Generamos un ordernumber simple y único (puedes cambiar el formato)
            $ordernumber = 'ORD-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4));

            // Creamos el maestro con total en 0 (se recalcula tras los items)
            $p = PedidosM::create([
                'idempresa'   => $data['idempresa'],
                'idusuario'   => $data['idusuario'],
                'notas'       => $data['notas'] ?? null,
                'total'       => 0,
                'ordernumber' => $ordernumber,
                'status'      => $data['status'] ?? 'pending',
                'estado'      => 'activo', // si lo quieres por defecto
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                PedidoProducto::create([
                    'idpedido'   => $p->idpedidosm,
                    'idproducts' => $item['idproducts'],
                    'cantidad'   => $item['cantidad'],
                    'precio'     => $item['precio'],
                    'notas'      => $item['notas'] ?? null,
                ]);

                $total += $item['cantidad'] * $item['precio'];
            }

            // Actualiza el total
            $p->update(['total' => $total]);

            return $p->load(['empresa', 'usuario', 'items.product']);
        });

        return response()->json([
            'message' => 'Pedido creado correctamente.',
            'data'    => $pedido,
        ], 201);
    }

    public function show(string $id)
    {
        $pedido = PedidosM::with(['empresa', 'usuario', 'items.product'])->findOrFail($id);
        return response()->json($pedido);
    }

    public function update(Request $request, string $id)
    {
        $pedido = PedidosM::findOrFail($id);

        $v = Validator::make($request->all(), [
            'idempresa' => 'sometimes|integer|exists:empresa,idempresa',
            'idusuario' => 'sometimes|integer|exists:users,idusuario',
            'notas'     => 'sometimes|nullable|string',
            'status'    => 'sometimes|nullable|string|max:50',
            'estado'    => 'sometimes|nullable|string|max:50',
            // Si quisieras soportar edición de items aquí, lo vemos luego.
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el pedido.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $pedido->update($v->validated());

        return response()->json([
            'message' => 'Pedido actualizado correctamente.',
            'data'    => $pedido->load(['empresa', 'usuario', 'items.product']),
        ]);
    }

    public function destroy(string $id)
    {
        $pedido = PedidosM::findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado.']);
    }
}