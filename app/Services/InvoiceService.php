<?php

namespace App\Services;

use App\Mail\InvoiceGenerated;
use App\Models\Invoice;
use App\Models\Lease;
use App\Enums\LeaseStatus;
use App\Enums\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    /**
     * Generate monthly rent invoices for all active leases
     */
    public function generateMonthlyInvoices(): int
    {
        $count = 0;
        $today = Carbon::today();
        $currentMonth = $today->format('Y-m');

        // Get all active leases
        $activeLeases = Lease::where('status', LeaseStatus::ACTIVE)
            ->with(['unit.property', 'tenant.user'])
            ->get();

        foreach ($activeLeases as $lease) {
            // Check if invoice already exists for this month
            $existingInvoice = Invoice::where('lease_id', $lease->id)
                ->whereYear('due_date', $today->year)
                ->whereMonth('due_date', $today->month)
                ->exists();

            if ($existingInvoice) {
                continue; // Skip if already generated
            }

            // Generate invoice
            $dueDate = Carbon::parse($currentMonth . '-05'); // Due on 5th of each month

            $invoice = Invoice::create([
                'lease_id' => $lease->id,
                'amount' => $lease->rent_amount,
                'due_date' => $dueDate,
                'status' => InvoiceStatus::PENDING,
                'description' => 'Monthly Rent - ' . $today->format('F Y'),
            ]);

            $invoice->setRelation('lease', $lease);
            Mail::to($lease->tenant->user->email)->queue(new InvoiceGenerated($invoice));

            $count++;
        }

        return $count;
    }

    /**
     * Generate a single invoice for a lease
     */
    public function generateInvoiceForLease(Lease $lease, Carbon $dueDate, ?string $description = null): Invoice
    {
        return Invoice::create([
            'lease_id' => $lease->id,
            'amount' => $lease->rent_amount,
            'due_date' => $dueDate,
            'status' => InvoiceStatus::PENDING,
            'description' => $description ?? 'Rent Invoice',
        ]);
    }
}
