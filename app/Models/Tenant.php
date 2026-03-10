<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUlids, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'user_id',
        'phone',
        'emergency_contact',
        'id_document',
        'national_id',
        'kra_pin',
        'occupation',
        'employer',
        'monthly_income',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_relationship',
        'guarantor_id',
        'occupants',
        'children',
        'has_pets',
        'pet_details',
        'preferred_contact',
    ];

    protected function casts(): array
    {
        return [
            'monthly_income' => 'decimal:2',
            'has_pets' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
