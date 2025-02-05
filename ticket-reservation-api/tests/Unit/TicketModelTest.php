<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ticket_code_is_unique()
    {
        $ticket1 = Ticket::factory()->create();
        $ticket2 = Ticket::factory()->create();

        $this->assertNotEquals($ticket1->ticket_code, $ticket2->ticket_code);
    }
}
