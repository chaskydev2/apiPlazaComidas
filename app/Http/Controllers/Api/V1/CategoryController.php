<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        // Puedes paginar si quieres: Category::paginate(15)
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        // Mapeamos imageUrl (camelCase) a image_url (DB)
        $payload = $request->all();
        if (isset($payload['imageUrl'])) {
            $payload['image_url'] = $payload['imageUrl'];
        }

        $validator = Validator::make($payload, [
            'category'    => 'required|string|max:255',
            'description' => 'required|string',
            'image_url'   => 'nullable|string|max:255',
        ], [
            'category.required'    => 'El nombre de la categoría es obligatorio.',
            'category.string'      => 'La categoría debe ser un texto válido.',
            'category.max'         => 'La categoría no debe exceder 255 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'description.string'   => 'La descripción debe ser un texto válido.',
            'image_url.string'     => 'La URL de imagen debe ser un texto.',
            'image_url.max'        => 'La URL de imagen no debe exceder 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la categoría.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $category = Category::create($data);

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'data'    => $category,
        ], 201);
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $payload = $request->all();
        if (isset($payload['imageUrl'])) {
            $payload['image_url'] = $payload['imageUrl'];
        }

        $validator = Validator::make($payload, [
            'category'    => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url'   => 'nullable|string|max:255',
        ], [
            'category.string'    => 'La categoría debe ser un texto válido.',
            'category.max'       => 'La categoría no debe exceder 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'image_url.string'   => 'La URL de imagen debe ser un texto.',
            'image_url.max'      => 'La URL de imagen no debe exceder 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación en la categoría.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $category->update($data);

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'data'    => $category,
        ]);
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada.']);
    }
}