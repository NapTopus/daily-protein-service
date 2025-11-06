<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_api_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();
        $this->freezeTime();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $send = function (): TestResponse {
            return $this->getJson(route('records.show', ['from' => '2025-08-12']));
        };
        for ($i = 0; $i < 120; $i++) {
            $send()->assertStatus(200);
        }
        $send()->assertStatus(429);

        $this->travel(61)->seconds();
        $send()->assertStatus(200);
    }

    #[Test]
    public function test_writes_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();
        $this->freezeTime();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $send = function (): TestResponse {
            return $this->postJson(route('items.store'), [
                "name"    => "egg",
                "protein" => 12
            ]);
        };
        for ($i = 0; $i < 30; $i++) {
            $send()->assertStatus(200);
        }
        $send()->assertStatus(429);

        $this->travel(61)->seconds();
        $send()->assertStatus(200);
    }

    #[Test]
    public function test_login_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();
        $this->freezeTime();

        $send = function (): TestResponse {
            return $this->postJson(route('auth.login'), [
                'email'    => fake()->unique()->safeEmail(),
                'password' => 'password',
            ]);
        };
        for ($i = 0; $i < 5; $i++) {
            $send()->assertStatus(401);
        }
        $send()->assertStatus(429);

        $this->travel(61)->seconds();
        $send()->assertStatus(401);
    }

    #[Test]
    public function test_register_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();

        $this->freezeTime();
        $send = function (): TestResponse {
            return $this->postJson(route('auth.register'), [
                'name'                 => fake()->name(),
                'email'                => fake()->unique()->safeEmail(),
                'password'             => 'password123',
                'passwordConfirmation' => 'password123'
            ]);
        };

        for ($i = 0; $i < 3; $i++) {
            $send()->assertStatus(200);
        }

        $send()->assertStatus(429);

        for ($i = 0; $i < 7; $i++) {
            $this->travel(61)->seconds();
            $send()->assertStatus(200);
        }

        $send()->assertStatus(429);

        $this->travel(61)->minutes();

        $send()->assertStatus(200);
    }

    #[Test]
    public function test_refresh_token_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();

        $this->freezeTime();
        $send = function (): TestResponse {
            return $this->withUnencryptedCookie('refreshToken', '123456789')->get(route('auth.refresh'));
        };

        for ($i = 0; $i < 5; $i++) {
            $send()->assertStatus(401);
        }

        $send()->assertStatus(429);

        $this->travel(1)->minutes();

        $send()->assertStatus(401);
    }

    #[Test]
    public function test_refresh_token_ip_rate_limit()
    {
        config()->set('cache.default', 'array');
        Cache::flush();

        $this->freezeTime();
        $send = function (): TestResponse {
            return $this->get(route('auth.refresh'));
        };

        for ($i = 0; $i < 60; $i++) {
            $send()->assertStatus(401);
        }

        $send()->assertStatus(429);

        $this->travel(1)->minutes();

        $send()->assertStatus(401);
    }
}
