<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Trip", properties: [
    new OA\Property(property: "start_date", type: "date", example: ""),
    new OA\Property(property: "end_date", type: "date", example: ""),
    new OA\Property(property: "trip_type", type: "string", example: ""),
    new OA\Property(property: "title", type: "string", example: ""),
    new OA\Property(property: "description", type: "string", example: "")
])]

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'trip_type',
        'title',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'location_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
