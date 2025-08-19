<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Store an uploaded image to AWS S3.
     */
    public function upload(Request $request)
    {

        dd($request->all());
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // hasta 10MB
                'folder' => 'nullable|string',
            ], [
                'file.required' => 'Debes seleccionar un archivo para subir.',
                'file.file' => 'El archivo no es válido.',
                'file.max' => 'El archivo no debe pesar más de 10MB.',
                'folder.string' => 'La carpeta debe ser un texto válido.',
            ]);

            $file = $request->file('file');
            $folder = $request->input('folder', 'uploads');
            $path = Storage::disk('s3')->put($folder, $file);
            $url = Storage::disk('s3')->url($path);

            return response()->json([
                'url' => $url,
                'path' => $path,
                'folder' => $folder,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Error de validación. Verifica los datos enviados.'
            ], 422);
        }
    }
}
