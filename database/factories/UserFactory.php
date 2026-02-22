<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+2547' . fake()->randomElement([0, 1, 2]) . fake()->numerify('#######'),
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function agent(): static
    {
        return $this->state(fn () => [
            'role' => UserRole::AGENT,
        ]);
    }

    public function landlord(): static
    {
        return $this->state(fn () => [
            'role' => UserRole::LANDLORD,
        ]);
    }

    public function tenant(): static
    {
        return $this->state(fn () => [
            'role' => UserRole::TENANT,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
