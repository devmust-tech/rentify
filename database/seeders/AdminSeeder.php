<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutGlobalScopes()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@rentify.test')],
            [
                'name'              => 'Super Admin',
                'organization_id'   => null, // Admin belongs to no org
                'role'              => UserRole::ADMIN,
                'status'            => UserStatus::ACTIVE,
                'password'          => Hash::make(env('ADMIN_PASSWORD', 'Admin@2026!')),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: ' . env('ADMIN_EMAIL', 'admin@rentify.test'));
    }
}
