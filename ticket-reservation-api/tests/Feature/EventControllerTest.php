<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase; // Her testten sonra database sıfırlanır.

    /** @test */
    public function it_fetches_all_events()
    {
        Event::factory(5)->create();

        $response = $this->getJson('/api/events');
        
        $response->assertStatus(200)
                ->assertJsonStructure(['*' => ['id', 'name', 'description', 'start_date', 'end_date']]);
    }

    /** @test */
    public function an_admin_can_create_an_event()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $venue = Venue::factory()->create();
        $response = $this->actingAs($admin)->postJson('/api/events', [
            'name' => 'Concert',
            'description' => 'Music event',
            'venue_id' => $venue->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
            'status' => 'upcoming'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name']);
    }
}

