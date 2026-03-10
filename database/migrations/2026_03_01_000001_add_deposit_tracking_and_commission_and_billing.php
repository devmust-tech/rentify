<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Deposit lifecycle timestamps on leases
        Schema::table('leases', function (Blueprint $table) {
            $table->timestamp('deposit_paid_at')->nullable()->after('deposit');
            $table->timestamp('deposit_refunded_at')->nullable()->after('deposit_paid_at');
        });

        // Commission tracking on payments
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('commission_amount', 15, 2)->nullable()->after('amount');
        });

        // Billing / subscription fields on organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('owner_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            $table->string('subscription_status')->nullable()->after('stripe_subscription_id'); // trialing|active|past_due|canceled
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_status');
        });

        // Escalation tracking on maintenance requests
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->timestamp('escalated_at')->nullable()->after('resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn(['deposit_paid_at', 'deposit_refunded_at']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id', 'stripe_subscription_id', 'subscription_status', 'trial_ends_at']);
        });
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn('escalated_at');
        });
    }
};
