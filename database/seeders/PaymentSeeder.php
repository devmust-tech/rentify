<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $paymentNum = 1;

        Invoice::where('status', InvoiceStatus::PAID)->each(function (Invoice $invoice) use (&$paymentNum) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_number' => 'PAY-' . str_pad($paymentNum++, 6, '0', STR_PAD_LEFT),
                'amount' => $invoice->amount,
                'method' => PaymentMethod::MPESA,
                'reference' => strtoupper(substr(md5(rand()), 0, 10)),
                'mpesa_receipt' => strtoupper('R' . substr(md5(rand()), 0, 8)),
                'status' => PaymentStatus::CONFIRMED,
                'paid_at' => $invoice->due_date->subDays(rand(0, 4)),
            ]);
        });
    }
}
