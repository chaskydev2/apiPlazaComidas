<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PedidosM;
use Illuminate\Http\Request;

class PedidosMController extends Controller
{
    public function index()
    {
        return PedidosM::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idmesa'        => 'required|exists:mesa,idmesa',
            'idservicio'    => 'required|exists:servicios,id',
            'idusuario'     => 'required|exists:users,idusuario',
            'idcliente'     => 'required|exists:cliente,idcliente',
            'total'         => 'required|numeric',
            'dateRegistro'  => 'required|date',
            'estado'        => 'required|string',
        ]);

        $pedido = PedidosM::create($data);

        return response()->json($pedido, 201);
    }

    public function show(string $id)
    {
        $pedido = PedidosM::findOrFail($id);
        return response()->json($pedido);
    }

    public function update(Request $request, string $id)
    {
        $pedido = PedidosM::findOrFail($id);

        $data = $request->validate([
            'idmesa'        => 'exists:mesa,idmesa',
            'idservicio'    => 'exists:servicios,id',
            'idusuario'     => 'exists:users,idusuario',
            'idcliente'     => 'exists:cliente,idcliente',
            'total'         => 'numeric',
            'dateRegistro'  => 'date',
            'estado'        => 'string',
        ]);

        $pedido->update($data);

        return response()->json($pedido);
    }

    public function destroy(string $id)
    {
        $pedido = PedidosM::findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado.']);
    }
}
