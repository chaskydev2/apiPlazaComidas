<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        return Mesa::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idempresa'   => 'required|exists:empresa,idempresa',
            'numeromesa'  => 'required|string',
            'qr'          => 'nullable|string',
        ]);

        $mesa = Mesa::create($data);

        return response()->json($mesa, 201);
    }

    public function show(string $id)
    {
        $mesa = Mesa::findOrFail($id);
        return response()->json($mesa);
    }

    public function update(Request $request, string $id)
    {
        $mesa = Mesa::findOrFail($id);

        $data = $request->validate([
            'idempresa'   => 'exists:empresa,idempresa',
            'numeromesa'  => 'string',
            'qr'          => 'nullable|string',
        ]);

        $mesa->update($data);

        return response()->json($mesa);
    }

    public function destroy(string $id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();

        return response()->json(['message' => 'Mesa eliminada.']);
    }
}
