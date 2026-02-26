<?php

namespace App\Models;

use App\Enums\AgreementStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentAgreement extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'agent_id',
        'landlord_id',
        'commission_rate',
        'payment_day',
        'start_date',
        'end_date',
        'terms',
        'status',
        'signed_at',
        'signature_url',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'signed_at' => 'datetime',
            'status' => AgreementStatus::class,
        ];
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function isActive(): bool
    {
        return $this->status === AgreementStatus::ACTIVE;
    }
}
