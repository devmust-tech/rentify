<?php

namespace App\Console\Commands;

use App\Enums\MaintenancePriority;
use App\Enums\NotificationType;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class EscalateMaintenanceRequests extends Command
{
    protected $signature = 'maintenance:escalate';
    protected $description = 'Escalate overdue high/urgent maintenance requests and notify agents';

    // How many days before escalation per priority
    private const THRESHOLDS = [
        'medium' => 5,
        'high'   => 3,
        'urgent' => 1,
    ];

    public function handle(NotificationService $notificationService): int
    {
        $escalated = 0;

        foreach (self::THRESHOLDS as $priority => $days) {
            $requests = MaintenanceRequest::where('priority', $priority)
                ->whereIn('status', ['pending', 'in_progress'])
                ->whereNull('escalated_at')
                ->where('created_at', '<=', now()->subDays($days))
                ->with(['unit.property', 'tenant.user'])
                ->get();

            foreach ($requests as $request) {
                $request->update(['escalated_at' => now()]);

                // Notify the agent who owns this property
                $property = $request->unit?->property;
                if ($property) {
                    $agent = \App\Models\User::find($property->agent_id);
                    if ($agent) {
                        $notificationService->notify(
                            user: $agent,
                            type: NotificationType::GENERAL,
                            subject: 'Maintenance Request Escalated',
                            message: "Request #{$request->id} ({$request->priority->label()}) for Unit {$request->unit->unit_number} at {$property->name} has been open for {$days}+ days. Please action it urgently.",
                            sendEmail: true,
                        );
                    }
                }

                $escalated++;
            }
        }

        $this->info("Escalated {$escalated} maintenance requests.");
        return Command::SUCCESS;
    }
}
