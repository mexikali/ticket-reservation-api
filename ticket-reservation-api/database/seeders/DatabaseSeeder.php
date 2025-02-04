<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Ticket;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        Venue::factory(5)->create();
        Event::factory(10)->create();
        Seat::factory(100)->create();
        Reservation::factory(20)->create();
        ReservationItem::factory(50)->create();
        Ticket::factory(50)->create();
    }
}

