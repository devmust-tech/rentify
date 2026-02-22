<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendRentReminders extends Command
{
    protected $signature = 'reminders:rent';
    protected $description = 'Send rent payment reminders for upcoming due dates';

    public function handle(NotificationService $notificationService): int
    {
        $upcomingInvoices = Invoice::with('lease.tenant.user')
            ->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(3)])
            ->get();

        $count = 0;
        foreach ($upcomingInvoices as $invoice) {
            $user = $invoice->lease->tenant->user ?? null;
            if ($user) {
                $notificationService->sendInApp(
                    $user,
                    'Rent Payment Reminder',
                    "Your rent of KSh " . number_format($invoice->amount, 2) . " is due on " . $invoice->due_date->format('d/m/Y') . "."
                );
                $count++;
            }
        }

        $this->info("Sent {$count} rent reminders.");
        return Command::SUCCESS;
    }
}
