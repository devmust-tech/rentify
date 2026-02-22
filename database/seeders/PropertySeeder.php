<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Enums\UserRole;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::where('role', UserRole::AGENT)->first();
        $landlords = Landlord::all();

        $properties = [
            ['name' => 'Sunrise Apartments', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Kilimani, Argwings Kodhek Rd', 'county' => '047', 'city' => 'Nairobi'],
            ['name' => 'Green Park Estate', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Langata, Langata Rd', 'county' => '047', 'city' => 'Nairobi'],
            ['name' => 'Jamii Residences', 'type' => PropertyType::RESIDENTIAL, 'address' => 'South B, Mombasa Rd', 'county' => '047', 'city' => 'Nairobi'],
            ['name' => 'Savanna Heights', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Westlands, Waiyaki Way', 'county' => '047', 'city' => 'Nairobi'],
            ['name' => 'Uhuru Towers', 'type' => PropertyType::COMMERCIAL, 'address' => 'CBD, Kenyatta Ave', 'county' => '047', 'city' => 'Nairobi'],
            ['name' => 'Maisha Apartments', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Ruaka, Limuru Rd', 'county' => '022', 'city' => 'Nairobi'],
            ['name' => 'Baraka Court', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Nyali, Links Rd', 'county' => '001', 'city' => 'Mombasa'],
            ['name' => 'Tumaini Flats', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Bamburi, Malindi Rd', 'county' => '001', 'city' => 'Mombasa'],
            ['name' => 'Amani Gardens', 'type' => PropertyType::MIXED, 'address' => 'Milimani, Oginga Odinga Rd', 'county' => '042', 'city' => 'Kisumu'],
            ['name' => 'Safari Villas', 'type' => PropertyType::RESIDENTIAL, 'address' => 'Section 58, Kenyatta Ave', 'county' => '032', 'city' => 'Nakuru'],
        ];

        foreach ($properties as $index => $data) {
            Property::create([
                'landlord_id' => $landlords[$index % $landlords->count()]->id,
                'agent_id' => $agent->id,
                'name' => $data['name'],
                'property_type' => $data['type'],
                'address' => $data['address'],
                'county' => $data['county'],
                'city' => $data['city'],
                'description' => 'Well-maintained property in a prime location.',
                'photos' => [],
            ]);
        }
    }
}
