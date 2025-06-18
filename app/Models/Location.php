<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Location", properties: [
    new OA\Property(property: "city", type: "string", example: ""),
    new OA\Property(property: "country", type: "string", example: ""),
    new OA\Property(property: "latitude", type: "string", example: ""),
    new OA\Property(property: "longitude", type: "string", example: "")
])]

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $fillable = [
        'city',
        'country',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
