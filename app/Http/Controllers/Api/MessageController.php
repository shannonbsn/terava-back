<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MessageController extends Controller
{
    #[OA\Get(
        path: '/api/messages',
        summary: 'Lister tous les messages',
        tags: ['Messages'],
        responses: [
            new OA\Response(response: 200, description: 'Succès')
        ]
    )]
    public function index()
    {
        $messages = Message::all();
        return response()->json($messages);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    #[OA\Post(
        path: '/api/messages',
        tags: ['Messages'],
        summary: 'Créer un nouveau message',
        requestBody: new OA\RequestBody(
            description: 'Données du message',
            required: true,
            content: new OA\JsonContent(ref: Message::class)
        ),
        responses: [
            new OA\Response(response: 201, description: 'Message créé avec succès')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_id' => 'required|exists:matches,id',
            'sender_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'sent_at' => 'nullable|date',
        ]);

        $message = Message::create($validated);
        return response()->json($message, 201);
    }

    #[OA\Get(
        path: '/api/messages/{id}',
        summary: 'Récupérer un message par ID',
        tags: ['Messages'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du message', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 404, description: 'Message non trouvé')
        ]
    )]
    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }
        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    #[OA\Put(
        path: '/api/messages/{id}',
        tags: ['Messages'],
        summary: 'Mettre à jour un message',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du message', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(description: 'Données du message à mettre à jour', required: true, content: new OA\JsonContent(ref: Message::class)),
        responses: [
            new OA\Response(response: 200, description: 'Message mis à jour avec succès'),
            new OA\Response(response: 404, description: 'Message non trouvé')
        ]
    )]
    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        $validated = $request->validate([
            'content' => 'sometimes|required|string',
            'sent_at' => 'nullable|date',
        ]);

        $message->update($validated);
        return response()->json($message);
    }

    #[OA\Delete(
        path: '/api/messages/{id}',
        tags: ['Messages'],
        summary: 'Supprimer un message',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du message', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Message supprimé avec succès'),
            new OA\Response(response: 404, description: 'Message non trouvé')
        ]
    )]
    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        $message->delete();
        return response()->json(null, 204);
    }
}
