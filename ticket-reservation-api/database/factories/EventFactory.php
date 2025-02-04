<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\Venue;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start_date = fake()->dateTimeBetween('+1 days', '+1 month');
        $end_date = (clone $start_date)->modify('+2 hours');

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'venue_id' => Venue::factory(),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => fake()->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
        ];
    }
}

