<?php

namespace App\Models;

use App\Enums\UnitStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, HasUlids, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'property_id',
        'unit_number',
        'floor_number',
        'size_sqm',
        'bedrooms',
        'bathrooms',
        'balcony',
        'furnishing',
        'rent_amount',
        'deposit_amount',
        'service_charge',
        'deposit_months',
        'billing_cycle',
        'available_from',
        'min_lease_months',
        'meter_type',
        'electricity_meter',
        'water_meter',
        'size',
        'status',
        'photos',
        'video_tour_url',
    ];

    protected function casts(): array
    {
        return [
            'rent_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'status' => UnitStatus::class,
            'photos' => 'array',
            'balcony' => 'boolean',
            'available_from' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class)->where('status', 'active');
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
