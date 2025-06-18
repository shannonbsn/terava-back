<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class ProfileController extends Controller
{
    #[OA\Get(
        path: '/api/profiles',
        summary: 'Lister tous les profils',
        tags: ['Profiles'],
        responses: [
            new OA\Response(response: 200, description: 'Succès')
        ]
    )]
    public function index()
    {
        $profiles = Profile::all();
        return response()->json($profiles);
    }

    #[OA\Get(
        path: '/api/profiles/{id}',
        summary: 'Récupérer un profil par ID',
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du profil', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Succès', content: new OA\JsonContent(ref: '#/components/schemas/Profile')),
            new OA\Response(response: 404, description: 'Profil ou utilisateur non trouvé')
        ]
    )]
    public function show($id)
    {
        $user = User::find($id);

        if (!$user || !$user->profile) {
            return response()->json(['message' => 'Profil ou utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user->profile);
    }

    #[OA\Post(
        path: '/api/profiles',
        tags: ['Profiles'],
        summary: 'Créer un nouveau profil',
        requestBody: new OA\RequestBody(
            description: 'Données du profil',
            required: true,
            content: new OA\JsonContent(ref: Profile::class)
        ),
        responses: [
            new OA\Response(response: 201, description: 'Profil créé avec succès')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'gender' => 'required|in:homme,femme',
            'birthdate' => 'required|date',
            'bio' => 'nullable|string',
            'interests' => 'nullable|string',
            'profile_picture' => 'nullable|string|url',
            'user_id' => 'required|exists:users,id',
        ]);

        $profile = Profile::create($validated);
        return response()->json($profile, 201);
    }

    #[OA\Put(
        path: '/api/profiles/{id}',
        tags: ['Profiles'],
        summary: 'Mettre à jour un profil',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du profil', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(description: 'Données du profil à mettre à jour', required: true, content: new OA\JsonContent(ref: Profile::class)),
        responses: [
            new OA\Response(response: 200, description: 'Profil mis à jour avec succès'),
            new OA\Response(response: 404, description: 'Profil non trouvé')
        ]
    )]
    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profil non trouvé'], 404);
        }

        $validated = $request->validate([
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'gender' => 'nullable|string|max:50',
            'birthdate' => 'nullable|date',
            'bio' => 'nullable|string',
            'interests' => 'nullable|string',
            'profile_picture' => 'nullable|string|url',
        ]);

        $profile->update($validated);
        return response()->json($profile);
    }

    #[OA\Delete(
        path: '/api/profiles/{id}',
        tags: ['Profiles'],
        summary: 'Supprimer un profil',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID du profil', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Profil supprimé avec succès'),
            new OA\Response(response: 404, description: 'Profil non trouvé')
        ]
    )]
    public function destroy($id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profil non trouvé'], 404);
        }

        $profile->delete();
        return response()->json(null, 204);
    }
}
