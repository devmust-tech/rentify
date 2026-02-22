<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('landlord_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('address');
            $table->string('county');
            $table->string('property_type');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();

            $table->index('county');
            $table->index('property_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
