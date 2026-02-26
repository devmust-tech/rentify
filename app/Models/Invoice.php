<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'lease_id',
        'amount',
        'due_date',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'status' => InvoiceStatus::class,
        ];
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->amount - $this->total_paid;
    }

    /**
     * Recalculate and update invoice status based on payments received.
     */
    public function updateStatus(): void
    {
        $totalPaid = $this->total_paid;
        $amount = (float) $this->amount;

        if ($totalPaid >= $amount) {
            $this->update(['status' => InvoiceStatus::PAID]);
        } elseif ($totalPaid > 0) {
            $this->update(['status' => InvoiceStatus::PARTIALLY_PAID]);
        }
    }
}
