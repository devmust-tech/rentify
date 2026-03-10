<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Properties ──
        Schema::table('properties', function (Blueprint $table) {
            $table->year('year_built')->nullable()->after('description');
            $table->year('last_renovated')->nullable()->after('year_built');
            $table->unsignedTinyInteger('total_floors')->nullable()->after('last_renovated');
            $table->unsignedSmallInteger('total_units_count')->nullable()->after('total_floors');

            // Location
            $table->decimal('latitude', 10, 7)->nullable()->after('county');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

            // Infrastructure
            $table->string('parking_type')->nullable()->after('longitude'); // none, open, covered, underground, street
            $table->boolean('ev_charging')->default(false)->after('parking_type');
            $table->boolean('fiber_ready')->default(false)->after('ev_charging');
            $table->boolean('backup_power')->default(false)->after('fiber_ready');
            $table->unsignedInteger('water_storage_liters')->nullable()->after('backup_power');

            // Policies
            $table->string('pet_policy')->nullable()->after('water_storage_liters'); // allowed, not_allowed, case_by_case
            $table->string('smoking_policy')->nullable()->after('pet_policy'); // allowed, not_allowed, designated_areas

            // Security
            $table->json('security_features')->nullable()->after('smoking_policy'); // ['cctv','gate_access','guards','smart_locks','intercom']
        });

        // ── Units ──
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedTinyInteger('floor_number')->nullable()->after('unit_number');
            $table->decimal('size_sqm', 8, 2)->nullable()->after('floor_number');
            $table->unsignedTinyInteger('bedrooms')->nullable()->after('size_sqm');
            $table->unsignedTinyInteger('bathrooms')->nullable()->after('bedrooms');
            $table->boolean('balcony')->default(false)->after('bathrooms');
            $table->string('furnishing')->nullable()->after('balcony'); // unfurnished, semi_furnished, furnished
            $table->decimal('service_charge', 12, 2)->nullable()->after('deposit_amount');
            $table->unsignedTinyInteger('deposit_months')->nullable()->after('service_charge');
            $table->string('billing_cycle')->default('monthly')->after('deposit_months'); // monthly, quarterly
            $table->date('available_from')->nullable()->after('billing_cycle');
            $table->unsignedSmallInteger('min_lease_months')->nullable()->after('available_from');
            $table->string('meter_type')->nullable()->after('min_lease_months'); // shared, individual
            $table->string('electricity_meter')->nullable()->after('meter_type');
            $table->string('water_meter')->nullable()->after('electricity_meter');
            $table->json('photos')->nullable()->after('water_meter');
            $table->string('video_tour_url')->nullable()->after('photos');
        });

        // ── Tenants ──
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('national_id')->nullable()->after('id_document');
            $table->string('kra_pin')->nullable()->after('national_id');
            $table->string('occupation')->nullable()->after('kra_pin');
            $table->string('employer')->nullable()->after('occupation');
            $table->decimal('monthly_income', 12, 2)->nullable()->after('employer');

            // Guarantor
            $table->string('guarantor_name')->nullable()->after('monthly_income');
            $table->string('guarantor_phone')->nullable()->after('guarantor_name');
            $table->string('guarantor_relationship')->nullable()->after('guarantor_phone');
            $table->string('guarantor_id')->nullable()->after('guarantor_relationship');

            // Household
            $table->unsignedTinyInteger('occupants')->nullable()->after('guarantor_id');
            $table->unsignedTinyInteger('children')->nullable()->after('occupants');
            $table->boolean('has_pets')->default(false)->after('children');
            $table->string('pet_details')->nullable()->after('has_pets');

            // Preferences
            $table->string('preferred_contact')->default('phone')->after('pet_details'); // phone, whatsapp, email, sms
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'year_built', 'last_renovated', 'total_floors', 'total_units_count',
                'latitude', 'longitude',
                'parking_type', 'ev_charging', 'fiber_ready', 'backup_power', 'water_storage_liters',
                'pet_policy', 'smoking_policy', 'security_features',
            ]);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'floor_number', 'size_sqm', 'bedrooms', 'bathrooms', 'balcony',
                'furnishing', 'service_charge', 'deposit_months', 'billing_cycle',
                'available_from', 'min_lease_months',
                'meter_type', 'electricity_meter', 'water_meter',
                'photos', 'video_tour_url',
            ]);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'national_id', 'kra_pin', 'occupation', 'employer', 'monthly_income',
                'guarantor_name', 'guarantor_phone', 'guarantor_relationship', 'guarantor_id',
                'occupants', 'children', 'has_pets', 'pet_details', 'preferred_contact',
            ]);
        });
    }
};
