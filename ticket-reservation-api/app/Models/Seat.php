<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'section',
        'row',
        'number',
        'status',
        'price'
    ];

    // Venue ilişkisi (Bir koltuk bir mekana aittir)
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
