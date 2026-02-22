<?php

namespace Database\Factories;

use App\Enums\MaintenancePriority;
use App\Enums\MaintenanceStatus;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRequestFactory extends Factory
{
    public function definition(): array
    {
        $issues = [
            'Leaking tap in the kitchen',
            'Broken window in the bedroom',
            'Toilet not flushing properly',
            'Electrical socket not working',
            'Water heater malfunction',
            'Door lock needs replacement',
            'Ceiling leak during rain',
            'Blocked drainage pipe',
            'Cracked wall needs repair',
            'Gate motor not working',
            'Security light burnt out',
            'Painting needed in living room',
        ];

        return [
            'unit_id' => Unit::factory(),
            'tenant_id' => Tenant::factory(),
            'title' => fake()->randomElement($issues),
            'description' => fake()->paragraph(2),
            'priority' => fake()->randomElement(MaintenancePriority::cases()),
            'status' => MaintenanceStatus::OPEN,
            'photos' => [],
            'resolved_at' => null,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn () => [
            'status' => MaintenanceStatus::RESOLVED,
            'resolved_at' => now(),
        ]);
    }
}
