<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_number' => 'PAY-' . fake()->unique()->numerify('######'),
            'amount' => fake()->randomElement([10000, 15000, 20000, 25000, 30000]),
            'method' => PaymentMethod::MPESA,
            'reference' => strtoupper(fake()->bothify('??########')),
            'mpesa_receipt' => strtoupper(fake()->bothify('??#????##')),
            'status' => PaymentStatus::CONFIRMED,
            'paid_at' => now(),
            'notes' => null,
        ];
    }

    public function mpesa(): static
    {
        return $this->state(fn () => [
            'method' => PaymentMethod::MPESA,
            'mpesa_receipt' => strtoupper(fake()->bothify('??#????##')),
        ]);
    }

    public function cash(): static
    {
        return $this->state(fn () => [
            'method' => PaymentMethod::CASH,
            'mpesa_receipt' => null,
        ]);
    }
}
