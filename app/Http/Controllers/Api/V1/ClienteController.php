<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return Cliente::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|string',
            'correo' => 'required|email|unique:cliente,correo',
            'nombre' => 'required|string',
        ]);

        $cliente = Cliente::create($data);

        return response()->json($cliente, 201);
    }

    public function show(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'numero' => 'string',
            'correo' => 'email|unique:cliente,correo,' . $id . ',idcliente',
            'nombre' => 'string',
        ]);

        $cliente->update($data);

        return response()->json($cliente);
    }

    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado.']);
    }
}
