<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;

class PedidoProductoController extends Controller
{
    public function index()
    {
        return PedidoProducto::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idpedido'      => 'required|exists:pedidos_m,idpedidosm',
            'idproducts'    => 'required|exists:products,id',
            'datoRegistro'  => 'required|date',
            'estado'        => 'required|string',
        ]);

        $pedidoProducto = PedidoProducto::create($data);

        return response()->json($pedidoProducto, 201);
    }

    public function show(string $id)
    {
        $pedidoProducto = PedidoProducto::findOrFail($id);
        return response()->json($pedidoProducto);
    }

    public function update(Request $request, string $id)
    {
        $pedidoProducto = PedidoProducto::findOrFail($id);

        $data = $request->validate([
            'idpedido'      => 'exists:pedidos_m,idpedidosm',
            'idproducts'    => 'exists:products,id',
            'datoRegistro'  => 'date',
            'estado'        => 'string',
        ]);

        $pedidoProducto->update($data);

        return response()->json($pedidoProducto);
    }

    public function destroy(string $id)
    {
        $pedidoProducto = PedidoProducto::findOrFail($id);
        $pedidoProducto->delete();

        return response()->json(['message' => 'Pedido-producto eliminado.']);
    }
}
