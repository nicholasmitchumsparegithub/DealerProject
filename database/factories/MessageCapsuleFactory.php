<?php

namespace Database\Factories;

use App\Models\MessageCapsule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MessageCapsule>
 */
class MessageCapsuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),  // Assumes User model exists
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'open_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'is_opened' => false,
        ];
    }
}
