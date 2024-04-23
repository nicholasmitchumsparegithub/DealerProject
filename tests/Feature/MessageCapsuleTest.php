<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\MessageCapsule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageCapsuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_retrieve_their_unopened_capsules()
    {
        $user = User::factory()->create();
        $capsules = MessageCapsule::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/message-capsules');
        $response->assertStatus(200)
            ->assertJson(['messageCapsules' => $capsules->toArray()]);
    }

    public function test_unauthenticated_user_cannot_access_capsules()
    {
        $response = $this->getJson('/api/message-capsules');
        $response->assertStatus(401);
    }

    public function test_user_can_create_a_message_capsule()
    {
        $user = User::factory()->create();
        $capsuleData = [
            'title' => 'Future Message',
            'message' => 'Hello Future Me!',
            'open_date' => now()->addYear()->toDateString(),
        ];

        $response = $this->actingAs($user)->postJson('/api/message-capsules', $capsuleData);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Message capsule created successfully!',
                'capsule' => [
                    'title' => 'Future Message',
                    'message' => 'Hello Future Me!'
                ]
            ]);
    }

    public function test_creating_a_message_capsule_requires_valid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/message-capsules', []);
        $response->assertStatus(422);
    }

    public function test_user_can_view_a_capsule()
    {
        $user = User::factory()->create();
        $capsule = MessageCapsule::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/message-capsules/{$capsule->id}");
        $response->assertStatus(200)
            ->assertJson(['messageCapsule' => $capsule->toArray()]);
    }

    public function test_viewing_a_nonexistent_capsule_returns_not_found()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/message-capsules/99');
        $response->assertStatus(404);
    }

    public function test_user_can_update_their_capsule_after_open_date()
    {
        $user = User::factory()->create();
        $capsule = MessageCapsule::factory()->create([
            'user_id' => $user->id,
            'open_date' => now()->subDay()  // Past date
        ]);

        $response = $this->actingAs($user)->putJson("/api/message-capsules/{$capsule->id}", ['message' => 'Updated Message']);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Message capsule updated successfully.']);
    }

    public function test_user_cannot_update_capsule_before_open_date()
    {
        $user = User::factory()->create();
        $capsule = MessageCapsule::factory()->create([
            'user_id' => $user->id,
            'open_date' => now()->addDay()  // Future date
        ]);

        $response = $this->actingAs($user)->putJson("/api/message-capsules/{$capsule->id}", ['message' => 'Updated Message']);
        $response->assertStatus(403);
    }

}
