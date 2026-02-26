<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_negotiations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('lease_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('proposed_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('proposed_rent', 15, 2);
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index('lease_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_negotiations');
    }
};
