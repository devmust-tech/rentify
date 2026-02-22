<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;

class MarkOverdueInvoices extends Command
{
    protected $signature = 'invoices:mark-overdue';
    protected $description = 'Mark past-due invoices as overdue';

    public function handle(InvoiceService $service): int
    {
        $count = $service->markOverdueInvoices();
        $this->info("Marked {$count} invoices as overdue.");
        return Command::SUCCESS;
    }
}
