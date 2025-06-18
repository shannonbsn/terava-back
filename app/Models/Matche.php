<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matche extends Model
{
    use HasFactory;

    protected $fillable = ['trip1_id', 'trip2_id', 'matched_at', 'status'];

    // Relations avec les trips
    public function trip1()
    {
        return $this->belongsTo(Trip::class, 'trip1_id');
    }

    public function trip2()
    {
        return $this->belongsTo(Trip::class, 'trip2_id');
    }
}
