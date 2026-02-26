<?php

namespace App\Models;

use App\Enums\NegotiationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentNegotiation extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'lease_id',
        'proposed_by',
        'proposed_rent',
        'message',
        'status',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'proposed_rent' => 'decimal:2',
            'status' => NegotiationStatus::class,
            'responded_at' => 'datetime',
        ];
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }
}
