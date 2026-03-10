<?php

namespace App\Models;

use App\Enums\LeaseStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lease extends Model
{
    use HasFactory, HasUlids, BelongsToOrganization, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'tenant_id',
        'unit_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit',
        'deposit_paid_at',
        'deposit_refunded_at',
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
            'deposit_paid_at' => 'datetime',
            'deposit_refunded_at' => 'datetime',
        ];
    }

    public function depositStatus(): string
    {
        if ($this->deposit_refunded_at) return 'refunded';
        if ($this->deposit_paid_at) return 'paid';
        return 'unpaid';
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
