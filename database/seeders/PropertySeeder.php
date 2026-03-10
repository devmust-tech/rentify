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
            ['name' => 'Sunrise Apartments',  'type' => PropertyType::APARTMENT,  'address' => 'Kilimani, Argwings Kodhek Rd', 'county' => '047'],
            ['name' => 'Green Park Estate',    'type' => PropertyType::HOUSE,      'address' => 'Langata, Langata Rd',          'county' => '047'],
            ['name' => 'Jamii Residences',     'type' => PropertyType::APARTMENT,  'address' => 'South B, Mombasa Rd',          'county' => '047'],
            ['name' => 'Savanna Heights',      'type' => PropertyType::APARTMENT,  'address' => 'Westlands, Waiyaki Way',       'county' => '047'],
            ['name' => 'Uhuru Towers',         'type' => PropertyType::COMMERCIAL, 'address' => 'CBD, Kenyatta Ave',            'county' => '047'],
            ['name' => 'Maisha Apartments',    'type' => PropertyType::APARTMENT,  'address' => 'Ruaka, Limuru Rd',             'county' => '022'],
            ['name' => 'Baraka Court',         'type' => PropertyType::APARTMENT,  'address' => 'Nyali, Links Rd',              'county' => '001'],
            ['name' => 'Tumaini Flats',        'type' => PropertyType::APARTMENT,  'address' => 'Bamburi, Malindi Rd',          'county' => '001'],
            ['name' => 'Amani Gardens',        'type' => PropertyType::COMMERCIAL, 'address' => 'Milimani, Oginga Odinga Rd',   'county' => '042'],
            ['name' => 'Safari Villas',        'type' => PropertyType::HOUSE,      'address' => 'Section 58, Kenyatta Ave',     'county' => '032'],
        ];

        foreach ($properties as $index => $data) {
            Property::create([
                'landlord_id' => $landlords[$index % $landlords->count()]->id,
                'agent_id' => $agent->id,
                'name' => $data['name'],
                'property_type' => $data['type'],
                'address' => $data['address'],
                'county' => $data['county'],
                'description' => 'Well-maintained property in a prime location.',
                'photos' => [],
            ]);
        }
    }
}
