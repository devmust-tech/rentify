<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lease_id' => Lease::factory(),
            'invoice_number' => 'INV-' . fake()->unique()->numerify('######'),
            'amount' => fake()->randomElement([10000, 15000, 20000, 25000, 30000, 35000, 45000]),
            'due_date' => now()->day(5),
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'status' => InvoiceStatus::PENDING,
            'notes' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => InvoiceStatus::PAID]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => InvoiceStatus::OVERDUE,
            'due_date' => now()->subDays(15),
            'period_start' => now()->subMonth()->startOfMonth(),
            'period_end' => now()->subMonth()->endOfMonth(),
        ]);
    }
}
