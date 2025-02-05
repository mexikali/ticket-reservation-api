<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\User;
use App\Models\Seat;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_book_an_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $seat = Seat::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/reservations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'seat_ids' => [$seat->id]
        ]);

        $response->assertStatus(201);
    }
}
