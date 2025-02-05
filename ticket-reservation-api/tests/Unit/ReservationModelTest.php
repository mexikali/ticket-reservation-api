<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReservationModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_reservation_expiry()
    {
        $reservation = Reservation::factory()->create([
            'expires_at' => Carbon::now()->subMinutes(20) // 20 dakika önce
        ]);

        $this->assertTrue($reservation->isExpired());
    }
}
