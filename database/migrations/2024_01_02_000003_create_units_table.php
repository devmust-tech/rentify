<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('property_id')->constrained()->cascadeOnDelete();
            $table->string('unit_number');
            $table->decimal('rent_amount', 15, 2);
            $table->decimal('deposit_amount', 15, 2)->nullable();
            $table->string('size')->nullable();
            $table->string('status')->default('vacant');
            $table->timestamps();

            $table->unique(['property_id', 'unit_number']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
