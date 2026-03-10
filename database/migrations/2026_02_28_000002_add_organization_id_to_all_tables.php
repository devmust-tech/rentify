<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('organization_id', 26)->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropIndex([$t->getTable().'_organization_id_index']);
                $t->dropColumn('organization_id');
            });
        }
    }
};
