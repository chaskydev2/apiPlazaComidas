<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PedidoProductoController extends Controller
{
    public function index(Request $request)
    {
        // filtros opcionales
        $idpedido   = $request->input('idpedido');
        $idproducts = $request->input('idproducts');

        $q = PedidoProducto::with('product', 'pedido');

        if (!empty($idpedido)) {
            $q->where('idpedido', $idpedido);
        }
        if (!empty($idproducts)) {
            $q->where('idproducts', $idproducts);
        }

        return response()->json($q->get());
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'idpedido'   => 'required|integer|exists:pedidos_m,idpedidosm',
            'idproducts' => 'required|integer|exists:products,id',
            'cantidad'   => 'required|integer|min:1',
            'precio'     => 'required|numeric|min:0',
            'notas'      => 'nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el item.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $item = PedidoProducto::create($v->validated());

        return response()->json($item->load('product', 'pedido'), 201);
    }

    public function show(string $id)
    {
        $item = PedidoProducto::with('product', 'pedido')->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, string $id)
    {
        $item = PedidoProducto::findOrFail($id);

        $v = Validator::make($request->all(), [
            'idpedido'   => 'sometimes|integer|exists:pedidos_m,idpedidosm',
            'idproducts' => 'sometimes|integer|exists:products,id',
            'cantidad'   => 'sometimes|integer|min:1',
            'precio'     => 'sometimes|numeric|min:0',
            'notas'      => 'sometimes|nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Errores de validación en el item.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $item->update($v->validated());

        return response()->json($item->load('product', 'pedido'));
    }

    public function destroy(string $id)
    {
        $item = PedidoProducto::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item eliminado.']);
    }
}
