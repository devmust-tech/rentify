<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->tenant(),
            'phone' => '+2547' . fake()->randomElement([0, 1, 2]) . fake()->numerify('#######'),
            'emergency_contact' => json_encode([
                'name' => fake()->name(),
                'phone' => '+2547' . fake()->randomElement([0, 1, 2]) . fake()->numerify('#######'),
                'relationship' => fake()->randomElement(['Spouse', 'Parent', 'Sibling', 'Friend']),
            ]),
            'id_document' => null,
        ];
    }
}
