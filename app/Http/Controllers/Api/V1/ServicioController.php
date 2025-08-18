<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        return Servicio::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipodeservicio' => 'required|string',
        ]);

        $servicio = Servicio::create($data);

        return response()->json($servicio, 201);
    }

    public function show(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        return response()->json($servicio);
    }

    public function update(Request $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);

        $data = $request->validate([
            'tipodeservicio' => 'string',
        ]);

        $servicio->update($data);

        return response()->json($servicio);
    }

    public function destroy(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return response()->json(['message' => 'Servicio eliminado.']);
    }
}
