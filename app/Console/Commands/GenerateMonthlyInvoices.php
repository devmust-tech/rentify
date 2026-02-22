<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate';
    protected $description = 'Generate monthly rent invoices for all active leases';

    public function handle(InvoiceService $service): int
    {
        $count = $service->generateMonthlyInvoices();
        $this->info("Generated {$count} invoices.");
        return Command::SUCCESS;
    }
}
