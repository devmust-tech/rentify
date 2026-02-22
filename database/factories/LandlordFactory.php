<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LandlordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->landlord(),
            'national_id' => fake()->numerify('########'),
            'payment_details' => [
                'mpesa_phone' => '+2547' . fake()->randomElement([0, 1, 2]) . fake()->numerify('#######'),
                'bank_name' => fake()->randomElement(['KCB Bank', 'Equity Bank', 'Co-operative Bank', 'ABSA Kenya', 'Stanbic Bank']),
                'account_number' => fake()->numerify('############'),
            ],
        ];
    }
}
