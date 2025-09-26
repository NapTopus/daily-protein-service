<?php

namespace Tests\Feature;

use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteEndPointTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_favorite()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            "name"    => "egg",
            "protein" => 12
        ];

        $response = $this->postJson(route('favorites.store'), $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            "name"    => "egg",
            "protein" => 12
        ]);
    }

    #[Test]
    public function it_can_query_favorites()
    {
        $user      = User::factory()->create();
        $favorite1 = Favorite::factory()->for($user)->create();
        $favorite2 = Favorite::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('favorites.index'));
        $response->assertStatus(200);

        $expected = FavoriteResource::collection([$favorite1, $favorite2])->resolve();
        $response->assertExactJson($expected);
    }

    #[Test]
    public function it_can_delete_favorite()
    {
        $user     = User::factory()->create();
        $favorite = Favorite::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->delete(route('favorites.destroy', ['favorite' => $favorite->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);
    }

    #[Test]
    public function it_cannot_delete_another_user_favorite()
    {
        $user        = User::factory()->create();
        $anotherUser = User::factory()->create();
        $favorite    = Favorite::factory()->for($anotherUser)->create();
        Sanctum::actingAs($user);

        $response = $this->delete(route('favorites.destroy', ['favorite' => $favorite->id]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('favorites', ['id' => $favorite->id]);
    }
}
