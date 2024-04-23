<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Nick Mitchum',
            'email' => 'nick@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Registration successful. Please log in to continue.']);
        $this->assertDatabaseHas('users', ['email' => 'nick@example.com']);
    }

    public function test_a_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'nick@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'nick@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    public function test_a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'nick@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'nick@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid Credentials']);
    }

    public function test_a_user_can_logout()
    {
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out successfully!']);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

}
