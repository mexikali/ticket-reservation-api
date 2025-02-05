<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Reservation;

class ReservationTest extends TestCase
{
    /** @test */
    public function it_creates_a_reservation_correctly()
    {
        $reservation = new Reservation([
            'user_id' => 1,
            'event_id' => 1,
            'status' => 'pending',
            'total_amount' => 100.00,
            'expires_at' => now()->addMinutes(15)
        ]);

        $this->assertEquals(1, $reservation->user_id);
        $this->assertEquals('pending', $reservation->status);
        $this->assertEquals(100.00, $reservation->total_amount);
    }
}

