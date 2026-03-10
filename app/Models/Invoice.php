<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, HasUlids, BelongsToOrganization, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'lease_id',
        'invoice_number',
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
     * Always refreshes from DB first to avoid stale cached data.
     */
    public function updateStatus(): void
    {
        // Fresh query to avoid cached relationship data
        $totalPaid = (float) $this->payments()->where('status', 'completed')->sum('amount');
        $amount    = (float) $this->amount;

        if ($totalPaid >= $amount && $amount > 0) {
            $this->update(['status' => InvoiceStatus::PAID]);
        } elseif ($totalPaid > 0) {
            $this->update(['status' => InvoiceStatus::PARTIALLY_PAID]);
        }
        // If totalPaid == 0 we leave the status as-is (pending or overdue)
    }
}
