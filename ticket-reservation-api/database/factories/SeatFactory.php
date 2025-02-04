<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seat;
use App\Models\Venue;

class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),
            'section' => fake()->optional()->word(),
            'row' => fake()->randomLetter(),
            'number' => fake()->numberBetween(1, 50),
            'status' => 'available',
            'price' => fake()->randomFloat(2, 10, 200),
        ];
    }
}

