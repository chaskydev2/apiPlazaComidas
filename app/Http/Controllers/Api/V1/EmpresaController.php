<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => 'required|string',
            'ubicacion'  => 'required|string',
            'direccion'  => 'required|string',
            'sucursal'   => 'required|string',
            'horario'    => 'nullable|string',
        ]);

        $empresa = Empresa::create($data);

        return response()->json($empresa, 201);
    }

    public function show(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return response()->json($empresa);
    }

    public function update(Request $request, string $id)
    {
        $empresa = Empresa::findOrFail($id);

        $data = $request->validate([
            'nombre'     => 'string',
            'ubicacion'  => 'string',
            'direccion'  => 'string',
            'sucursal'   => 'string',
            'horario'    => 'nullable|string',
        ]);

        $empresa->update($data);

        return response()->json($empresa);
    }

    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return response()->json(['message' => 'Empresa eliminada.']);
    }
}
