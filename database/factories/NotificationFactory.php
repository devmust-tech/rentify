<?php

namespace Database\Factories;

use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => NotificationType::IN_APP,
            'subject' => fake()->sentence(4),
            'message' => fake()->paragraph(),
            'read_at' => fake()->optional(0.3)->dateTimeBetween('-7 days', 'now'),
            'sent_at' => now(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn () => ['read_at' => null]);
    }

    public function email(): static
    {
        return $this->state(fn () => ['type' => NotificationType::EMAIL]);
    }
}
