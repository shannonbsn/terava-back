<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "User",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "username", type: "string", example: "john_doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T12:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T12:00:00Z"),
    ]
)]
class UserSchema {}


class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'accept_policy',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    // protected static function booted()
    // {
    //     static::created(function (User $user) {
    //         // Créer un profil associé à l'utilisateur
    //         $user->profile()->create([
    //             'firstname' => '', // Exemple : utiliser le nom de l'utilisateur comme username
    //             'lastname' => '', // Valeur par défaut
    //             'location' => '', // Valeur par défaut
    //             'interests' => '', // Valeur par défaut
    //             'bio' => '', // Valeur par défaut
    //             'profile_picture' => '', // Valeur par défaut
    //         ]);
    //     });
    // }
}
