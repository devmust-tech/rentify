<?php

namespace App\Models;

use App\Enums\LeaseStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'unit_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit',
        'terms',
        'status',
        'signed_at',
        'signature_url',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'rent_amount' => 'decimal:2',
            'deposit' => 'decimal:2',
            'status' => LeaseStatus::class,
            'signed_at' => 'datetime',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function negotiations()
    {
        return $this->hasMany(RentNegotiation::class);
    }

    public function isActive(): bool
    {
        return $this->status === LeaseStatus::ACTIVE;
    }
}
