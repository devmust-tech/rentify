<?php

namespace Database\Seeders;

use App\Enums\UnitStatus;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $unitTypes = [
            ['type' => 'Bedsitter', 'bedrooms' => 0, 'bathrooms' => 1, 'rent' => 8000],
            ['type' => 'Studio', 'bedrooms' => 0, 'bathrooms' => 1, 'rent' => 12000],
            ['type' => '1 Bedroom', 'bedrooms' => 1, 'bathrooms' => 1, 'rent' => 15000],
            ['type' => '1 Bedroom', 'bedrooms' => 1, 'bathrooms' => 1, 'rent' => 18000],
            ['type' => '2 Bedroom', 'bedrooms' => 2, 'bathrooms' => 1, 'rent' => 25000],
            ['type' => '2 Bedroom', 'bedrooms' => 2, 'bathrooms' => 2, 'rent' => 30000],
            ['type' => '3 Bedroom', 'bedrooms' => 3, 'bathrooms' => 2, 'rent' => 45000],
        ];

        $floors = ['Ground', '1st', '2nd', '3rd', '4th'];

        Property::all()->each(function (Property $property) use ($unitTypes, $floors) {
            $unitCount = rand(4, 7);
            for ($i = 1; $i <= $unitCount; $i++) {
                $type = $unitTypes[array_rand($unitTypes)];
                $status = $i <= ($unitCount - 1) ? UnitStatus::OCCUPIED : UnitStatus::VACANT;

                Unit::create([
                    'property_id' => $property->id,
                    'unit_number' => strtoupper(chr(64 + ceil($i / 2)) . str_pad($i, 2, '0', STR_PAD_LEFT)),
                    'unit_type' => $type['type'],
                    'floor' => $floors[($i - 1) % count($floors)],
                    'bedrooms' => $type['bedrooms'],
                    'bathrooms' => $type['bathrooms'],
                    'size_sqft' => rand(250, 1500),
                    'rent_amount' => $type['rent'],
                    'deposit_amount' => $type['rent'],
                    'status' => $status,
                ]);
            }
        });
    }
}
