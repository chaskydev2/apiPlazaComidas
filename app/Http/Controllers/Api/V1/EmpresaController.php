<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function store(Request $request)
    {
        // Validaciones con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'nombre'     => 'required|string|max:255',
            'ubicacion'  => 'required|string|max:255',
            'direccion'  => 'required|string|max:255',
            'sucursal'   => 'required|string|max:255',
            'horario'    => 'nullable|string|max:255',
        ], [
            'nombre.required'     => 'El campo nombre de la empresa es obligatorio.',
            'nombre.string'       => 'El nombre de la empresa debe ser un texto válido.',
            'ubicacion.required'  => 'El campo ubicación es obligatorio.',
            'ubicacion.string'    => 'La ubicación debe ser un texto válido.',
            'direccion.required'  => 'El campo dirección es obligatorio.',
            'direccion.string'    => 'La dirección debe ser un texto válido.',
            'sucursal.required'   => 'El campo sucursal es obligatorio.',
            'sucursal.string'     => 'La sucursal debe ser un texto válido.',
            'horario.string'      => 'El horario debe ser un texto válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la empresa.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $empresa = Empresa::create($validator->validated());

        return response()->json([
            'message' => 'Empresa creada correctamente.',
            'data'    => $empresa
        ], 201);
    }

    public function show(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return response()->json($empresa);
    }

    public function update(Request $request, string $id)
    {
        $empresa = Empresa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre'     => 'sometimes|string|max:255',
            'ubicacion'  => 'sometimes|string|max:255',
            'direccion'  => 'sometimes|string|max:255',
            'sucursal'   => 'sometimes|string|max:255',
            'horario'    => 'nullable|string|max:255',
        ], [
            'nombre.string'       => 'El nombre de la empresa debe ser un texto válido.',
            'ubicacion.string'    => 'La ubicación debe ser un texto válido.',
            'direccion.string'    => 'La dirección debe ser un texto válido.',
            'sucursal.string'     => 'La sucursal debe ser un texto válido.',
            'horario.string'      => 'El horario debe ser un texto válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la empresa.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $empresa->update($validator->validated());

        return response()->json([
            'message' => 'Empresa actualizada correctamente.',
            'data'    => $empresa
        ]);
    }

    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return response()->json(['message' => 'Empresa eliminada.']);
    }
}