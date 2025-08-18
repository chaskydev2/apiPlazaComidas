<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idempresa' => 'required|exists:empresa,idempresa',
            'numero'    => 'required|string',
            'message'   => 'required|string',
            'response'  => 'nullable|string',
        ]);

        $message = Message::create($data);

        return response()->json($message, 201);
    }

    public function show(string $id)
    {
        $message = Message::findOrFail($id);
        return response()->json($message);
    }

    public function update(Request $request, string $id)
    {
        $message = Message::findOrFail($id);

        $data = $request->validate([
            'idempresa' => 'exists:empresa,idempresa',
            'numero'    => 'string',
            'message'   => 'string',
            'response'  => 'nullable|string',
        ]);

        $message->update($data);

        return response()->json($message);
    }

    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Mensaje eliminado.']);
    }
}
