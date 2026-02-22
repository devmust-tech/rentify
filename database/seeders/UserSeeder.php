<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Agent (admin)
        User::create([
            'name' => 'James Mwangi',
            'email' => 'agent@rentify.co.ke',
            'phone' => '+254712345678',
            'role' => UserRole::AGENT,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Landlords
        $landlordData = [
            ['name' => 'Catherine Wanjiku', 'email' => 'catherine@rentify.co.ke', 'phone' => '+254722111001'],
            ['name' => 'Peter Odhiambo', 'email' => 'peter@rentify.co.ke', 'phone' => '+254733222002'],
            ['name' => 'Amina Hassan', 'email' => 'amina@rentify.co.ke', 'phone' => '+254711333003'],
            ['name' => 'David Kimani', 'email' => 'david@rentify.co.ke', 'phone' => '+254700444004'],
            ['name' => 'Grace Akinyi', 'email' => 'grace@rentify.co.ke', 'phone' => '+254723555005'],
        ];

        foreach ($landlordData as $data) {
            User::create(array_merge($data, [
                'role' => UserRole::LANDLORD,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]));
        }

        // Tenants
        $tenantData = [
            ['name' => 'Brian Kipchoge', 'email' => 'brian@rentify.co.ke', 'phone' => '+254712600001'],
            ['name' => 'Lucy Wambui', 'email' => 'lucy@rentify.co.ke', 'phone' => '+254722600002'],
            ['name' => 'Hassan Ali', 'email' => 'hassan@rentify.co.ke', 'phone' => '+254733600003'],
            ['name' => 'Faith Muthoni', 'email' => 'faith@rentify.co.ke', 'phone' => '+254700600004'],
            ['name' => 'Samuel Otieno', 'email' => 'samuel@rentify.co.ke', 'phone' => '+254711600005'],
            ['name' => 'Mercy Chebet', 'email' => 'mercy@rentify.co.ke', 'phone' => '+254723600006'],
            ['name' => 'Joseph Mutua', 'email' => 'joseph@rentify.co.ke', 'phone' => '+254712600007'],
            ['name' => 'Esther Nyambura', 'email' => 'esther@rentify.co.ke', 'phone' => '+254700600008'],
            ['name' => 'Daniel Kiprono', 'email' => 'daniel@rentify.co.ke', 'phone' => '+254733600009'],
            ['name' => 'Agnes Moraa', 'email' => 'agnes@rentify.co.ke', 'phone' => '+254711600010'],
            ['name' => 'Kevin Njoroge', 'email' => 'kevin@rentify.co.ke', 'phone' => '+254722600011'],
            ['name' => 'Rose Adhiambo', 'email' => 'rose@rentify.co.ke', 'phone' => '+254712600012'],
            ['name' => 'Patrick Maina', 'email' => 'patrick@rentify.co.ke', 'phone' => '+254700600013'],
            ['name' => 'Jane Nyokabi', 'email' => 'jane@rentify.co.ke', 'phone' => '+254723600014'],
            ['name' => 'Michael Omondi', 'email' => 'michael@rentify.co.ke', 'phone' => '+254733600015'],
            ['name' => 'Elizabeth Wangari', 'email' => 'elizabeth@rentify.co.ke', 'phone' => '+254711600016'],
            ['name' => 'John Kamau', 'email' => 'john@rentify.co.ke', 'phone' => '+254712600017'],
            ['name' => 'Sarah Jepkoech', 'email' => 'sarah@rentify.co.ke', 'phone' => '+254722600018'],
            ['name' => 'Martin Wekesa', 'email' => 'martin@rentify.co.ke', 'phone' => '+254700600019'],
            ['name' => 'Nancy Atieno', 'email' => 'nancy@rentify.co.ke', 'phone' => '+254733600020'],
        ];

        foreach ($tenantData as $data) {
            User::create(array_merge($data, [
                'role' => UserRole::TENANT,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]));
        }
    }
}
