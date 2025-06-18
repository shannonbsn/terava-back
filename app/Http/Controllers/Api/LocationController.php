<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/locations',
        summary: 'Liste toutes les locations',
        tags: ['Locations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Succès'
            )
        ]
    )]
    public function index()
    {
        $locations = Location::all();
        return response()->json($locations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/locations',
        tags: ['Locations'],
        summary: 'Créer une nouvelle location',
        requestBody: new OA\RequestBody(
            description: 'Données de la location',
            required: true,
            content: new OA\JsonContent(ref: Location::class)
        ),
        responses: [
            new OA\Response(response: 201, description: 'Location créée avec succès')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = Location::create($validated);
        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/locations/{id}',
        summary: 'Récupérer une location par ID',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de la location',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
            new OA\Response(response: 404, description: 'Location non trouvée')
        ]
    )]
    public function show($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location non trouvée'], 404);
        }
        return response()->json($location);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/locations/{id}',
        tags: ['Locations'],
        summary: 'Mettre à jour une location',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID de la location', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(description: 'Données de la location à mettre à jour', required: true, content: new OA\JsonContent(ref: Location::class)),
        responses: [
            new OA\Response(response: 200, description: 'Location mise à jour avec succès'),
            new OA\Response(response: 404, description: 'Location non trouvée')
        ]
    )]
    public function update(Request $request, $id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location non trouvée'], 404);
        }

        $validated = $request->validate([
            'city' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
        ]);

        $location->update($validated);
        return response()->json($location);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/locations/{id}',
        tags: ['Locations'],
        summary: 'Supprimer une location',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID de la location', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Location supprimée avec succès'),
            new OA\Response(response: 404, description: 'Location non trouvée')
        ]
    )]
    public function destroy($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location non trouvée'], 404);
        }

        $location->delete();
        return response()->json(null, 204);
    }
}
