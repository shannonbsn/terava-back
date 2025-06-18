<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MatcheController extends Controller
{
    #[OA\Get(
        path: '/api/matches',
        summary: 'Liste toutes les correspondances',
        tags: ['Matches'],
        responses: [
            new OA\Response(response: 200, description: 'Succès')
        ]
    )]
    public function index()
    {
        $matches = Matche::all();
        return response()->json($matches);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    #[OA\Post(
        path: '/api/matches',
        tags: ['Matches'],
        summary: 'Créer une nouvelle correspondance',
        requestBody: new OA\RequestBody(
            description: 'Données de la correspondance',
            required: true,
            content: new OA\JsonContent(ref: Matche::class)
        ),
        responses: [
            new OA\Response(response: 201, description: 'Correspondance créée avec succès')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip1_id' => 'required|exists:trips,id',
            'trip2_id' => 'required|exists:trips,id',
            'matched_at' => 'nullable|date',
            'status' => 'required|string|in:pending,confirmed,declined',
        ]);

        $match = Matche::create($validated);
        return response()->json($match, 201);
    }

    #[OA\Get(
        path: '/api/matches/{id}',
        summary: 'Récupérer une correspondance par ID',
        tags: ['Matches'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID de la correspondance', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 404, description: 'Correspondance non trouvée')
        ]
    )]
    public function show($id)
    {
        $match = Matche::find($id);
        if (!$match) {
            return response()->json(['message' => 'Correspondance non trouvée'], 404);
        }
        return response()->json($match);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matche $matche)
    {
        //
    }

    #[OA\Put(
        path: '/api/matches/{id}',
        tags: ['Matches'],
        summary: 'Mettre à jour une correspondance',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID de la correspondance', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(description: 'Données de la correspondance à mettre à jour', required: true, content: new OA\JsonContent(ref: Matche::class)),
        responses: [
            new OA\Response(response: 200, description: 'Correspondance mise à jour avec succès'),
            new OA\Response(response: 404, description: 'Correspondance non trouvée')
        ]
    )]
    public function update(Request $request, $id)
    {
        $match = Matche::find($id);
        if (!$match) {
            return response()->json(['message' => 'Correspondance non trouvée'], 404);
        }

        $validated = $request->validate([
            'trip1_id' => 'sometimes|required|exists:trips,id',
            'trip2_id' => 'sometimes|required|exists:trips,id',
            'matched_at' => 'nullable|date',
            'status' => 'sometimes|required|string|in:pending,confirmed,declined',
        ]);

        $match->update($validated);
        return response()->json($match);
    }

    #[OA\Delete(
        path: '/api/matches/{id}',
        tags: ['Matches'],
        summary: 'Supprimer une correspondance',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID de la correspondance', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Correspondance supprimée avec succès'),
            new OA\Response(response: 404, description: 'Correspondance non trouvée')
        ]
    )]
    public function destroy($id)
    {
        $match = Matche::find($id);
        if (!$match) {
            return response()->json(['message' => 'Correspondance non trouvée'], 404);
        }

        $match->delete();
        return response()->json(null, 204);
    }
}
