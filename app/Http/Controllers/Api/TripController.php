<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trips",
     *     summary="Récupérer tous les voyages",
     *     tags={"Trips"},
     *     @OA\Response(response="200", description="Liste des voyages"),
     * )
     */
    public function index()
    {
        return response()->json(Trip::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/trips",
     *     summary="Créer un voyage",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"start_date", "end_date", "trip_type", "title", "description"},
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date"),
     *             @OA\Property(property="trip_type", type="string"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Voyage créé"),
     *     @OA\Response(response="400", description="Erreur de validation"),
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'trip_type' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $trip = Trip::create($validatedData);
        return response()->json($trip, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/trips/{id}",
     *     summary="Récupérer un voyage par son ID",
     *     tags={"Trips"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Détails du voyage"),
     *     @OA\Response(response="404", description="Voyage non trouvé"),
     * )
     */
    public function show($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Voyage non trouvé'], 404);
        }

        return response()->json($trip, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/trips/{id}",
     *     summary="Mettre à jour un voyage",
     *     tags={"Trips"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date"),
     *             @OA\Property(property="trip_type", type="string"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Voyage mis à jour"),
     *     @OA\Response(response="404", description="Voyage non trouvé"),
     *     @OA\Response(response="400", description="Erreur de validation"),
     * )
     */
    public function update(Request $request, $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Voyage non trouvé'], 404);
        }

        $validatedData = $request->validate([
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'trip_type' => 'string',
            'title' => 'string',
            'description' => 'string',
        ]);

        $trip->update($validatedData);
        return response()->json($trip, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/trips/{id}",
     *     summary="Supprimer un voyage",
     *     tags={"Trips"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Voyage supprimé"),
     *     @OA\Response(response="404", description="Voyage non trouvé"),
     * )
     */
    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Voyage non trouvé'], 404);
        }

        $trip->delete();
        return response()->json(['message' => 'Voyage supprimé'], 200);
    }
}
