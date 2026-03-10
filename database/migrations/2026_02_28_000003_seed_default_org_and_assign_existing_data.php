<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $tables = [
        'users',
        'landlords',
        'tenants',
        'properties',
        'units',
        'leases',
        'invoices',
        'payments',
        'maintenance_requests',
        'maintenance_notes',
        'notifications',
        'agent_agreements',
        'rent_negotiations',
    ];

    public function up(): void
    {
        $orgId = (string) Str::ulid();

        DB::table('organizations')->insert([
            'id'            => $orgId,
            'name'          => 'Demo Agency',
            'slug'          => 'demo',
            'primary_color' => '#4f46e5',
            'accent_color'  => '#6366f1',
            'status'        => 'active',
            'owner_id'      => null,
            'settings'      => json_encode(['timezone' => 'Africa/Nairobi', 'currency' => 'KES']),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        foreach ($this->tables as $table) {
            DB::table($table)
                ->whereNull('organization_id')
                ->update(['organization_id' => $orgId]);
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::table($table)->update(['organization_id' => null]);
        }

        DB::table('organizations')->where('slug', 'demo')->delete();
    }
};
