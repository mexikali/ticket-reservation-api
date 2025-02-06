<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_limits_requests_per_minute()
    {
        RateLimiter::clear('api'); // Test sırasında rate limit cache temizleniyor.
        $user = User::factory()->create();
        for ($i = 0; $i < 101; $i++) {
            $response = $this->actingAs($user)->getJson('/api/events');
            
            if ($i < 100) {
                $response->assertStatus(200); // İlk 100 istek başarılı olmalı.
            } else {
                $response->assertStatus(429); // 101. istekte Too Many Requests hatası bekleniyor.
            }
        }
    }
}

