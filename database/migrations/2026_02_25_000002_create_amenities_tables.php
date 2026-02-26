<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('category'); // utility, facility, service
            $table->string('icon')->nullable(); // SVG path data
            $table->timestamps();
        });

        Schema::create('property_amenity', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('property_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('amenity_id')->constrained()->cascadeOnDelete();
            $table->boolean('included_in_rent')->default(true);
            $table->string('provider')->nullable();
            $table->decimal('monthly_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_amenity');
        Schema::dropIfExists('amenities');
    }
};
