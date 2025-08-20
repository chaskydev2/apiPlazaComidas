<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Show a single image URL from AWS S3 by its path.
     */
    public function show(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ],[
            'path.required' => 'Debes proporcionar la ruta de la imagen.'
        ]);

        $path = $request->input('path');
        if (Storage::disk('s3')->exists($path)) {
            $url = Storage::disk('s3')->url($path);
            return response()->json([
                'url' => $url,
                'path' => $path
            ]);
        } else {
            return response()->json([
                'message' => 'La imagen no existe en S3.',
                'path' => $path
            ], 404);
        }
    }
    /**
     * Store an uploaded image to AWS S3.
     */
    public function upload(Request $request)
    {
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

    /**
     * Delete an image from AWS S3.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ], [
            'path.required' => 'Debes proporcionar la ruta de la imagen a eliminar.'
        ]);

        $path = $request->input('path');
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
            return response()->json(['message' => 'Imagen eliminada correctamente.', 'path' => $path]);
        } else {
            return response()->json(['message' => 'La imagen no existe en S3.', 'path' => $path], 404);
        }
    }

    /**
     * Upload multiple images to AWS S3.
     */
    public function uploadMultiple(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240',
            'folder' => 'nullable|string',
        ], [
            'files.required' => 'Debes seleccionar al menos un archivo para subir.',
            'files.array' => 'El campo files debe ser un arreglo de archivos.',
            'files.*.file' => 'Uno de los archivos no es válido.',
            'files.*.max' => 'Uno de los archivos supera el tamaño máximo de 10MB.',
            'folder.string' => 'La carpeta debe ser un texto válido.',
        ]);

        $folder = $request->input('folder', 'uploads');
        $urls = [];
        $paths = [];
        foreach ($request->file('files') as $file) {
            $path = Storage::disk('s3')->put($folder, $file);
            $url = Storage::disk('s3')->url($path);
            $urls[] = $url;
            $paths[] = $path;
        }

        return response()->json([
            'urls' => $urls,
            'paths' => $paths,
            'folder' => $folder,
            'count' => count($urls)
        ]);
    }
}
