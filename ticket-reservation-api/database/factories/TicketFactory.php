<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\Reservation;
use App\Models\Seat;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'seat_id' => Seat::factory(),
            'ticket_code' => Str::upper(fake()->unique()->bothify('TICKET-#####')),
            'status' => 'valid',
        ];
    }
}

