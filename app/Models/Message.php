<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Message", properties: [
    new OA\Property(property: "match_id", type: "integer", example: ""),
    new OA\Property(property: "sender_id", type: "integer", example: ""),
    new OA\Property(property: "content", type: "string", example: ""),
    new OA\Property(property: "sent_at", type: "string", format: "date-time", example: "")
])]

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'match_id',
        'sender_id',
        'content',
        'sent_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'match_id' => 'integer',
        'sender_id' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function matche()
    {
        return $this->belongsTo(Matche::class, 'match_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}
