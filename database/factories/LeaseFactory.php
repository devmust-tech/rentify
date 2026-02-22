<?php

namespace Database\Factories;

use App\Enums\LeaseStatus;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $endDate = (clone $startDate)->modify('+12 months');

        return [
            'tenant_id' => Tenant::factory(),
            'unit_id' => Unit::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'rent_amount' => fake()->randomElement([10000, 15000, 20000, 25000, 30000, 35000, 45000]),
            'deposit_amount' => fake()->randomElement([10000, 15000, 20000, 25000, 30000, 35000, 45000]),
            'status' => LeaseStatus::ACTIVE,
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => LeaseStatus::ACTIVE]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => LeaseStatus::EXPIRED,
            'start_date' => now()->subMonths(18),
            'end_date' => now()->subMonths(6),
        ]);
    }
}
