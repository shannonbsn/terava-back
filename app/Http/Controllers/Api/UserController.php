<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Mon API Laravel'
)]
class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        summary: 'Liste tous les utilisateurs',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Succès'
            )
        ]
    )]
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            // eager load the profile relationship
            $user->profile = User::with('profile')->find($user->id)->profile;
        }
        return response()->json($users);
    }

    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Récupérer un utilisateur par ID',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Succès',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur non trouvé'
            )
        ]
    )]
    public function show($id)
    {
        $user = User::with('profile')->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    #[OA\Get(
        path: '/api/users/{id}/profil',
        summary: 'Récupérer le profil d\'un utilisateur par ID',
        operationId: 'getUserProfile',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Succès',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur non trouvé'
            )
        ]
    )]
    public function getUserProfile($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // Récupérer le profil de l'utilisateur
        $profile = $user->profile;
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        return response()->json($profile);
    }

    #[OA\Post(
        path: '/api/users',
        summary: 'Créer un nouvel utilisateur',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à créer',
            content: new OA\JsonContent(
                type: 'object',
                required: ['username', 'email', 'password', 'accept_policy'],
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'john_doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'securePassword123'),
                    new OA\Property(property: 'accept_policy', type: 'boolean', example: true)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur créé avec succès',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation'
            )
        ]
    )]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'accept_policy' => 'required|boolean',
            ]);

            $user = \App\Models\User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'accept_policy' => $validated['accept_policy'],
            ]);

            return response()->json($user, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    #[OA\Put(
        path: '/api/users/{id}',
        tags: ['Users'],
        summary: 'Mettre à jour un utilisateur',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Données utilisateur à mettre à jour',
            required: true,
            content: new OA\JsonContent(
                ref: User::class
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur mis à jour avec succès',
                content: new OA\JsonContent(
                    ref: User::class
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur non trouvé'
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update($request->all());
        return response()->json($user);
    }

    #[OA\Put(
        path: '/api/users/{id}/profil',
        summary: 'Mettre à jour le profil d\'un utilisateur',
        operationId: 'updateUserProfile',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Données du profil à mettre à jour',
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/Profile'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profil mis à jour avec succès',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Profile'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur ou profil non trouvé'
            )
        ]
    )]
    public function updateUserProfile(Request $request, $id)
    {
        // Vérifier si l'utilisateur existe
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Vérifier si le profil existe
        $profile = $user->profile;
        if (!$profile) {
            return response()->json(['message' => 'Profil non trouvé'], 404);
        }

        // Valider les données si nécessaire
        $validated = $request->validate([
            'username' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'interests' => 'sometimes|string',
            'bio' => 'sometimes|string',
            'profile_picture' => 'sometimes|string'
        ]);

        // Mettre à jour le profil
        $profile->update($validated);

        return response()->json($profile);
    }

    #[OA\Delete(
        path: '/api/users/{id}',
        tags: ['Users'],
        summary: 'Supprimer un utilisateur',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Utilisateur supprimé avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur non trouvé'
            )
        ]
    )]
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
