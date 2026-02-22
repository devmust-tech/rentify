<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenantUsers = User::where('role', UserRole::TENANT)->get();

        $relationships = ['Spouse', 'Parent', 'Sibling', 'Friend', 'Relative'];

        foreach ($tenantUsers as $user) {
            Tenant::create([
                'user_id' => $user->id,
                'phone' => $user->phone,
                'emergency_contact' => json_encode([
                    'name' => fake()->name(),
                    'phone' => '+2547' . rand(0, 2) . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'relationship' => $relationships[array_rand($relationships)],
                ]),
                'id_document' => null,
            ]);
        }
    }
}
