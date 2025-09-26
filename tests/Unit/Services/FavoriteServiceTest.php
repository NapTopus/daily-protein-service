<?php

namespace Tests\Unit\Services;

use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\User;
use App\Services\FavoriteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FavoriteService $favoriteService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->favoriteService = app(FavoriteService::class);
    }

    #[Test]
    public function it_creates_favorite()
    {
        $user        = User::factory()->create();
        $requestData = [
            'name'    => 'Chicken',
            'protein' => 30.5
        ];

        $this->favoriteService->create($requestData, $user);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'name'    => 'Chicken',
            'protein' => 30.5
        ]);
    }

    #[Test]
    public function it_queries_all_favorites()
    {
        $user      = User::factory()->create();
        $favorite1 = Favorite::factory()->for($user)->create();
        $favorite2 = Favorite::factory()->for($user)->create();

        $expected = FavoriteResource::collection([$favorite1, $favorite2])->resolve();
        $actual   = $this->favoriteService->queryAll($user);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function it_deletes_favorite()
    {
        $favorite = Favorite::factory()->for(User::factory()->create())->create();
        $this->favoriteService->destroy($favorite);
        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);
    }
}
