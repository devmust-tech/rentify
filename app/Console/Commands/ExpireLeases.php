<?php

namespace App\Console\Commands;

use App\Enums\LeaseStatus;
use App\Enums\UnitStatus;
use App\Models\Lease;
use Illuminate\Console\Command;

class ExpireLeases extends Command
{
    protected $signature = 'leases:expire';
    protected $description = 'Automatically expire leases past their end date';

    public function handle(): int
    {
        $expired = Lease::where('status', LeaseStatus::Active)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->get();

        $count = 0;
        foreach ($expired as $lease) {
            $lease->update(['status' => LeaseStatus::Expired]);
            $lease->unit->update(['status' => UnitStatus::Vacant]);
            $count++;
        }

        $this->info("Expired {$count} leases.");
        return Command::SUCCESS;
    }
}
