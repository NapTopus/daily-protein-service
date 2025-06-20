<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Record;
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

    #[Test]
    public function it_can_update_item()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $record = Record::factory()->for($user)->has(Item::factory()->state(['name' => 'Chicken', 'protein' => 30.5]))->create();
        $item   = $record->items->first();

        $payload = [
            "protein" => 40
        ];

        $response = $this->patchJson('/api/item/' . $item->id, $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken',
            'protein'   => 40,
            'record_id' => $record->id
        ]);
    }
}
