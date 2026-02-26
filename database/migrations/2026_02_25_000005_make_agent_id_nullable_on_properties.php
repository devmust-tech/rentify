<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The agent_id column is already nullable in the original schema.
     * This migration exists as documentation that self-managed landlord
     * properties (where agent_id is null) are now a supported feature.
     */
    public function up(): void
    {
        // agent_id is already nullable in the original properties migration.
        // This migration is kept as documentation that self-managed landlord
        // properties (where agent_id is null) are now a supported feature.
    }

    public function down(): void
    {
        // No rollback needed - column was already nullable
    }
};
