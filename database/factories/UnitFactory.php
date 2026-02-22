<?php

namespace Database\Factories;

use App\Enums\UnitStatus;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        $types = ['Bedsitter', 'Studio', '1 Bedroom', '2 Bedroom', '3 Bedroom', 'Shop', 'Office'];
        $floors = ['Ground', '1st', '2nd', '3rd', '4th', '5th'];

        return [
            'property_id' => Property::factory(),
            'unit_number' => strtoupper(fake()->bothify('?##')),
            'unit_type' => fake()->randomElement($types),
            'floor' => fake()->optional(0.8)->randomElement($floors),
            'bedrooms' => fake()->numberBetween(0, 3),
            'bathrooms' => fake()->numberBetween(1, 2),
            'size_sqft' => fake()->optional(0.6)->numberBetween(200, 2000),
            'rent_amount' => fake()->randomElement([8000, 10000, 12000, 15000, 18000, 20000, 25000, 30000, 35000, 45000, 50000, 65000, 80000]),
            'deposit_amount' => null,
            'status' => UnitStatus::VACANT,
            'description' => fake()->optional(0.5)->sentence(8),
        ];
    }

    public function occupied(): static
    {
        return $this->state(fn () => ['status' => UnitStatus::OCCUPIED]);
    }

    public function vacant(): static
    {
        return $this->state(fn () => ['status' => UnitStatus::VACANT]);
    }
}
