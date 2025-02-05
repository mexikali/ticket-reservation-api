<?php

namespace Tests\Unit;

use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_dates_are_properly_formatted()
    {
        $event = Event::factory()->create();

        $this->assertTrue((bool) strtotime($event->start_date));
        $this->assertTrue((bool) strtotime($event->end_date));
    }
}
