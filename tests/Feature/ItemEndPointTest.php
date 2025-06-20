<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemEndPointTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_item()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            "name"    => "egg",
            "protein" => 12
        ];

        $response = $this->postJson('/api/item', $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('items', [
            "name"    => "egg",
            "protein" => 12
        ]);
    }
}
