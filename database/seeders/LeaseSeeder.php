<?php

namespace Database\Seeders;

use App\Enums\LeaseStatus;
use App\Enums\UnitStatus;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class LeaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();
        $occupiedUnits = Unit::where('status', UnitStatus::OCCUPIED)->get();

        $assignCount = min($tenants->count(), $occupiedUnits->count());

        for ($i = 0; $i < $assignCount; $i++) {
            $startDate = now()->subMonths(rand(1, 10));
            $endDate = (clone $startDate)->addMonths(12);

            Lease::create([
                'tenant_id' => $tenants[$i]->id,
                'unit_id' => $occupiedUnits[$i]->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rent_amount' => $occupiedUnits[$i]->rent_amount,
                'deposit_amount' => $occupiedUnits[$i]->deposit_amount ?? $occupiedUnits[$i]->rent_amount,
                'status' => LeaseStatus::ACTIVE,
                'notes' => null,
            ]);
        }
    }
}
