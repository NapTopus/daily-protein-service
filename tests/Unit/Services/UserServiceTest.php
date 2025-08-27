<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    #[Test]
    public function it_updates_user_default_target(): void
    {
        $user        = User::factory()->create();
        $newTarget   = 789.89;
        $requestData = [
            'target' => $newTarget,
        ];

        $this->userService->update($requestData, $user);

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'default_target' => $newTarget
        ]);
    }
}
