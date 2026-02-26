<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_agreements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('landlord_id')->constrained()->cascadeOnDelete();
            $table->decimal('commission_rate', 5, 2);
            $table->integer('payment_day');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('terms')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_url')->nullable();
            $table->timestamps();

            $table->unique(['agent_id', 'landlord_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_agreements');
    }
};
