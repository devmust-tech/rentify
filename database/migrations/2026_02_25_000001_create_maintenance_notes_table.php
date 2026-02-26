<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_notes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('maintenance_request_id')->constrained('maintenance_requests')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();

            $table->index('maintenance_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_notes');
    }
};
