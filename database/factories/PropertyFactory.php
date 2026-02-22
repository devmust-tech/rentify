<?php

namespace Database\Factories;

use App\Enums\PropertyType;
use App\Models\Landlord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $estates = ['Kilimani', 'Westlands', 'Lavington', 'Kileleshwa', 'South B', 'South C', 'Langata', 'Karen', 'Roysambu', 'Kasarani', 'Embakasi', 'Ruaka', 'Juja', 'Kitengela', 'Rongai', 'Syokimau', 'Athi River', 'Nyali', 'Bamburi', 'Kisumu CBD'];
        $propertyNames = ['Sunrise Apartments', 'Green Park Estate', 'Jamii Residences', 'Savanna Heights', 'Uhuru Towers', 'Maisha Apartments', 'Baraka Court', 'Tumaini Flats', 'Amani Gardens', 'Safari Villas', 'Taji Heights', 'Milele Residences', 'Furaha Courts', 'Imara Apartments', 'Neema Heights'];

        return [
            'landlord_id' => Landlord::factory(),
            'agent_id' => User::factory()->agent(),
            'name' => fake()->randomElement($propertyNames) . ' ' . fake()->buildingNumber(),
            'property_type' => fake()->randomElement(PropertyType::cases()),
            'address' => fake()->randomElement($estates) . ', ' . fake()->streetAddress(),
            'county' => fake()->randomElement(array_keys(config('counties', ['047' => 'Nairobi']))),
            'city' => fake()->randomElement(['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Thika', 'Nyeri']),
            'description' => fake()->optional(0.7)->sentence(10),
            'photos' => [],
        ];
    }

    public function residential(): static
    {
        return $this->state(fn () => ['property_type' => PropertyType::RESIDENTIAL]);
    }

    public function commercial(): static
    {
        return $this->state(fn () => ['property_type' => PropertyType::COMMERCIAL]);
    }
}
