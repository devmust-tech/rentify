<?php

namespace Database\Seeders;

use App\Enums\MaintenancePriority;
use App\Enums\MaintenanceStatus;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Seeder;

class MaintenanceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $issues = [
            ['title' => 'Leaking tap in the kitchen', 'priority' => MaintenancePriority::MEDIUM],
            ['title' => 'Broken window in the bedroom', 'priority' => MaintenancePriority::HIGH],
            ['title' => 'Toilet not flushing properly', 'priority' => MaintenancePriority::HIGH],
            ['title' => 'Electrical socket not working', 'priority' => MaintenancePriority::MEDIUM],
            ['title' => 'Water heater malfunction', 'priority' => MaintenancePriority::URGENT],
            ['title' => 'Door lock needs replacement', 'priority' => MaintenancePriority::HIGH],
            ['title' => 'Ceiling leak during rain', 'priority' => MaintenancePriority::URGENT],
            ['title' => 'Blocked drainage pipe', 'priority' => MaintenancePriority::MEDIUM],
            ['title' => 'Cracked wall needs repair', 'priority' => MaintenancePriority::LOW],
            ['title' => 'Security light burnt out', 'priority' => MaintenancePriority::LOW],
        ];

        $leases = Lease::with('tenant', 'unit')->get();

        foreach ($leases->take(8) as $index => $lease) {
            $issue = $issues[$index % count($issues)];
            $isResolved = $index < 3;

            MaintenanceRequest::create([
                'unit_id' => $lease->unit_id,
                'tenant_id' => $lease->tenant_id,
                'title' => $issue['title'],
                'description' => 'The issue needs attention. Please send someone to check.',
                'priority' => $issue['priority'],
                'status' => $isResolved ? MaintenanceStatus::RESOLVED : MaintenanceStatus::OPEN,
                'photos' => [],
                'resolved_at' => $isResolved ? now()->subDays(rand(1, 10)) : null,
            ]);
        }
    }
}
