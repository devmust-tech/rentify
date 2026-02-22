<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Landlord;
use App\Models\User;
use Illuminate\Database\Seeder;

class LandlordSeeder extends Seeder
{
    public function run(): void
    {
        $landlordUsers = User::where('role', UserRole::LANDLORD)->get();

        $banks = ['KCB Bank', 'Equity Bank', 'Co-operative Bank', 'ABSA Kenya', 'Stanbic Bank'];

        foreach ($landlordUsers as $index => $user) {
            Landlord::create([
                'user_id' => $user->id,
                'national_id' => '2' . str_pad(rand(1000000, 9999999), 7, '0'),
                'payment_details' => [
                    'mpesa_phone' => $user->phone,
                    'bank_name' => $banks[$index % count($banks)],
                    'account_number' => str_pad(rand(1000000000, 9999999999), 12, '0'),
                ],
            ]);
        }
    }
}
