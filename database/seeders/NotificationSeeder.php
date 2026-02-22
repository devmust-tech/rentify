<?php

namespace Database\Seeders;

use App\Enums\NotificationType;
use App\Enums\UserRole;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::where('role', UserRole::AGENT)->first();

        // Agent notifications
        Notification::create([
            'user_id' => $agent->id,
            'type' => NotificationType::IN_APP,
            'subject' => 'New Maintenance Request',
            'message' => 'A new maintenance request has been submitted for Sunrise Apartments, Unit A01.',
            'sent_at' => now(),
        ]);

        Notification::create([
            'user_id' => $agent->id,
            'type' => NotificationType::IN_APP,
            'subject' => 'Payment Received',
            'message' => 'Payment of KSh 25,000 received from Brian Kipchoge via M-PESA.',
            'sent_at' => now()->subHours(3),
            'read_at' => now()->subHours(1),
        ]);

        // Tenant notifications
        $tenants = User::where('role', UserRole::TENANT)->take(5)->get();
        foreach ($tenants as $tenant) {
            Notification::create([
                'user_id' => $tenant->id,
                'type' => NotificationType::IN_APP,
                'subject' => 'Invoice Generated',
                'message' => 'Your rent invoice for ' . now()->format('F Y') . ' has been generated. Amount: KSh ' . number_format(rand(10, 45) * 1000),
                'sent_at' => now()->startOfMonth(),
            ]);
        }
    }
}
