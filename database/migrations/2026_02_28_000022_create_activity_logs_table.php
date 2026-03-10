<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('organization_id')->nullable()->index();
            $table->string('user_id')->nullable()->index();
            $table->string('action', 100);              // e.g. 'invoice.created'
            $table->string('subject_type')->nullable();  // e.g. 'App\Models\Invoice'
            $table->string('subject_id')->nullable();
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
