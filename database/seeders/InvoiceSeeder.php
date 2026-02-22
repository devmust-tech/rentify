<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Lease;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoiceNum = 1;

        Lease::all()->each(function (Lease $lease) use (&$invoiceNum) {
            // Current month - pending
            Invoice::create([
                'lease_id' => $lease->id,
                'invoice_number' => 'INV-' . str_pad($invoiceNum++, 6, '0', STR_PAD_LEFT),
                'amount' => $lease->rent_amount,
                'due_date' => now()->day(5),
                'period_start' => now()->startOfMonth(),
                'period_end' => now()->endOfMonth(),
                'status' => InvoiceStatus::PENDING,
            ]);

            // Last month - paid
            Invoice::create([
                'lease_id' => $lease->id,
                'invoice_number' => 'INV-' . str_pad($invoiceNum++, 6, '0', STR_PAD_LEFT),
                'amount' => $lease->rent_amount,
                'due_date' => now()->subMonth()->day(5),
                'period_start' => now()->subMonth()->startOfMonth(),
                'period_end' => now()->subMonth()->endOfMonth(),
                'status' => InvoiceStatus::PAID,
            ]);

            // Some tenants have overdue from 2 months ago
            if (rand(0, 2) === 0) {
                Invoice::create([
                    'lease_id' => $lease->id,
                    'invoice_number' => 'INV-' . str_pad($invoiceNum++, 6, '0', STR_PAD_LEFT),
                    'amount' => $lease->rent_amount,
                    'due_date' => now()->subMonths(2)->day(5),
                    'period_start' => now()->subMonths(2)->startOfMonth(),
                    'period_end' => now()->subMonths(2)->endOfMonth(),
                    'status' => InvoiceStatus::OVERDUE,
                ]);
            }
        });
    }
}
