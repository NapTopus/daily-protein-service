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

        $response = $this->postJson(route('items.store'), $payload);
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

        $response = $this->patchJson(route('items.update', ['item' => $item->id]), ['protein' => 40]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('items', [
            'name'      => 'Chicken',
            'protein'   => 40,
            'record_id' => $record->id
        ]);
    }

    #[Test]
    public function it_cannot_update_item_without_login()
    {
        $item     = Item::factory()->for(Record::factory()->for(User::factory()))->create();
        $response = $this->patchJson(route('items.update', ['item' => $item->id]), ['protein' => 40]);
        $response->assertStatus(401);
    }

    #[Test]
    public function it_cannot_update_item_from_another_user()
    {
        $user        = User::factory()->has(Record::factory()->has(Item::factory()))->create();
        $item        = $user->records->first()->items->first();
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($anotherUser);

        $response = $this->patchJson(route('items.update', ['item' => $item->id]), ['protein' => 40]);
        $response->assertStatus(403);
    }

    #[Test]
    public function it_throw_exception_if_item_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->patchJson(route('items.update', ['item' => '9999']), ['protein' => 40]);
        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_delete_item()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $record = Record::factory()->for($user)->has(Item::factory())->create();
        $item   = $record->items->first();

        $response = $this->deleteJson(route('items.destroy', ['item' => $item->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    #[Test]
    public function it_cannot_delete_item_from_another_user()
    {
        $user        = User::factory()->has(Record::factory()->has(Item::factory()))->create();
        $item        = $user->records->first()->items->first();
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($anotherUser);

        $response = $this->deleteJson(route('items.destroy', ['item' => $item->id]));
        $response->assertStatus(403);
    }
}
