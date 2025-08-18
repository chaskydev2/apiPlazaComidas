<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mensaje;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return Mensaje::all();
    }

    public function store(Request $request)
    {
        $mensaje = Mensaje::create([
            'texto' => $request->texto,
            'origen' => $request->origen, // "chatbot" o "web"
            'tipo' => $request->tipo,     // "entrada" o "respuesta"
            'timestamp' => now(),
        ]);

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
