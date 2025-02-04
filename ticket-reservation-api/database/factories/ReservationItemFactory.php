<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ReservationItem;
use App\Models\Reservation;
use App\Models\Seat;

class ReservationItemFactory extends Factory
{
    protected $model = ReservationItem::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'seat_id' => Seat::factory(),
            'price' => fake()->randomFloat(2, 20, 200),
        ];
    }
}

