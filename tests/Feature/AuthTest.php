<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_register_and_login()
    {
        $name             = fake()->name();
        $email            = fake()->unique()->safeEmail();
        $password         = 'password123';
        $registerResponse = $this->postJson(route('auth.register'), [
            'name'                 => $name,
            'email'                => $email,
            'password'             => $password,
            'passwordConfirmation' => $password
        ]);
        $registerResponse->assertOk();

        $this->assertDatabaseHas('users', [
            'name'  => $name,
            'email' => $email,
        ]);

        $loginResponse = $this->postJson(route('auth.login'), [
            'email'    => $email,
            'password' => $password,
        ]);
        $loginResponse
            ->assertOk()
            ->assertJsonStructure(['authToken']);
    }
}
