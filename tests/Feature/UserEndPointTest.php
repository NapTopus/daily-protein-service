<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserEndPointTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_my_info(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->get(route('users.me'));
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'defaultTarget' => $user->default_target
            ]);
    }

    #[Test]
    public function it_can_update_default_target(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $newTarget = 789.89;

        $response = $this->patch(
            route('users.updateDefaultTarget', ['user' => $user->id]),
            [
                'target' => $newTarget
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'default_target' => $newTarget
        ]);
    }

    #[Test]
    public function it_cannot_update_default_target_from_another_user()
    {
        $user        = User::factory()->create();
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($user);
        $newTarget = 789.89;

        $response = $this->patch(
            route('users.updateDefaultTarget', ['user' => $anotherUser->id]),
            [
                'target' => $newTarget
            ]
        );

        $response->assertStatus(403);
    }
}
