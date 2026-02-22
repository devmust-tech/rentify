<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\LeaseStatus;
use App\Enums\MaintenancePriority;
use App\Enums\MaintenanceStatus;
use App\Enums\NotificationType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PropertyType;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Invoice;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedUsers();
        });
    }

    private function seedUsers(): void
    {
        // ────────────────────────────────────────────────────────────────
        // USERS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating users...');

        $agent = User::create([
            'name' => 'John Kamau',
            'email' => 'agent@rentify.co.ke',
            'phone' => '0712345678',
            'role' => UserRole::AGENT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $landlordUser1 = User::create([
            'name' => 'Mary Wanjiku',
            'email' => 'landlord@rentify.co.ke',
            'phone' => '0723456789',
            'role' => UserRole::LANDLORD,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $landlordUser2 = User::create([
            'name' => 'Peter Ochieng',
            'email' => 'landlord2@rentify.co.ke',
            'phone' => '0734567890',
            'role' => UserRole::LANDLORD,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $tenantUser1 = User::create([
            'name' => 'Grace Muthoni',
            'email' => 'tenant@rentify.co.ke',
            'phone' => '0745678901',
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $tenantUser2 = User::create([
            'name' => 'David Kiprop',
            'email' => 'tenant2@rentify.co.ke',
            'phone' => '0756789012',
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $tenantUser3 = User::create([
            'name' => 'Sarah Akinyi',
            'email' => 'tenant3@rentify.co.ke',
            'phone' => '0767890123',
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $tenantUser4 = User::create([
            'name' => 'James Mwangi',
            'email' => 'tenant4@rentify.co.ke',
            'phone' => '0778901234',
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $tenantUser5 = User::create([
            'name' => 'Lucy Njeri',
            'email' => 'tenant5@rentify.co.ke',
            'phone' => '0789012345',
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Created 8 users (1 agent, 2 landlords, 5 tenants)');

        // ────────────────────────────────────────────────────────────────
        // LANDLORDS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating landlord records...');

        $landlord1 = Landlord::create([
            'user_id' => $landlordUser1->id,
            'national_id' => '12345678',
            'payment_details' => [
                'bank' => 'KCB Bank',
                'account_name' => 'Mary Wanjiku',
                'account_number' => '1234567890',
            ],
        ]);

        $landlord2 = Landlord::create([
            'user_id' => $landlordUser2->id,
            'national_id' => '87654321',
            'payment_details' => [
                'bank' => 'Equity Bank',
                'account_name' => 'Peter Ochieng',
                'account_number' => '0987654321',
            ],
        ]);

        $this->command->info('Created 2 landlord records');

        // ────────────────────────────────────────────────────────────────
        // TENANTS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating tenant records...');

        $tenant1 = Tenant::create([
            'user_id' => $tenantUser1->id,
            'phone' => '0745678901',
            'emergency_contact' => '0700111222',
        ]);

        $tenant2 = Tenant::create([
            'user_id' => $tenantUser2->id,
            'phone' => '0756789012',
            'emergency_contact' => '0700333444',
        ]);

        $tenant3 = Tenant::create([
            'user_id' => $tenantUser3->id,
            'phone' => '0767890123',
            'emergency_contact' => '0700555666',
        ]);

        $tenant4 = Tenant::create([
            'user_id' => $tenantUser4->id,
            'phone' => '0778901234',
            'emergency_contact' => '0700777888',
        ]);

        $tenant5 = Tenant::create([
            'user_id' => $tenantUser5->id,
            'phone' => '0789012345',
            'emergency_contact' => '0700999000',
        ]);

        $this->command->info('Created 5 tenant records');

        // ────────────────────────────────────────────────────────────────
        // PROPERTIES
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating properties...');

        $sunrise = Property::create([
            'landlord_id' => $landlord1->id,
            'agent_id' => $agent->id,
            'name' => 'Sunrise Apartments',
            'address' => '123 Kenyatta Avenue',
            'county' => '047',
            'property_type' => PropertyType::APARTMENT,
            'description' => 'Modern apartments in the heart of Nairobi with 24/7 security, parking, and reliable water supply.',
        ]);

        $garden = Property::create([
            'landlord_id' => $landlord1->id,
            'agent_id' => $agent->id,
            'name' => 'Garden Villas',
            'address' => '45 Ngong Road',
            'county' => '047',
            'property_type' => PropertyType::HOUSE,
            'description' => 'Spacious villas with beautiful gardens, located along Ngong Road with easy access to the CBD.',
        ]);

        $businessHub = Property::create([
            'landlord_id' => $landlord2->id,
            'agent_id' => $agent->id,
            'name' => 'Business Hub Tower',
            'address' => '78 Moi Avenue',
            'county' => '001',
            'property_type' => PropertyType::COMMERCIAL,
            'description' => 'Premium commercial spaces in Mombasa with high-speed internet, elevator access, and ample parking.',
        ]);

        $this->command->info('Created 3 properties');

        // ────────────────────────────────────────────────────────────────
        // UNITS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating units...');

        // Sunrise Apartments (4 units)
        $unitA1 = Unit::create([
            'property_id' => $sunrise->id,
            'unit_number' => 'A1',
            'rent_amount' => 15000,
            'deposit_amount' => 15000,
            'size' => '1 Bedroom',
            'status' => UnitStatus::OCCUPIED,
        ]);

        $unitA2 = Unit::create([
            'property_id' => $sunrise->id,
            'unit_number' => 'A2',
            'rent_amount' => 18000,
            'deposit_amount' => 18000,
            'size' => '2 Bedroom',
            'status' => UnitStatus::OCCUPIED,
        ]);

        $unitB1 = Unit::create([
            'property_id' => $sunrise->id,
            'unit_number' => 'B1',
            'rent_amount' => 22000,
            'deposit_amount' => 22000,
            'size' => '2 Bedroom',
            'status' => UnitStatus::OCCUPIED,
        ]);

        $unitB2 = Unit::create([
            'property_id' => $sunrise->id,
            'unit_number' => 'B2',
            'rent_amount' => 22000,
            'deposit_amount' => 22000,
            'size' => '2 Bedroom',
            'status' => UnitStatus::VACANT,
        ]);

        // Garden Villas (3 units)
        $unitV1 = Unit::create([
            'property_id' => $garden->id,
            'unit_number' => 'V1',
            'rent_amount' => 35000,
            'deposit_amount' => 35000,
            'size' => '3 Bedroom',
            'status' => UnitStatus::OCCUPIED,
        ]);

        $unitV2 = Unit::create([
            'property_id' => $garden->id,
            'unit_number' => 'V2',
            'rent_amount' => 35000,
            'deposit_amount' => 35000,
            'size' => '3 Bedroom',
            'status' => UnitStatus::VACANT,
        ]);

        $unitV3 = Unit::create([
            'property_id' => $garden->id,
            'unit_number' => 'V3',
            'rent_amount' => 40000,
            'deposit_amount' => 40000,
            'size' => '4 Bedroom',
            'status' => UnitStatus::VACANT,
        ]);

        // Business Hub Tower (3 units)
        $unitS1 = Unit::create([
            'property_id' => $businessHub->id,
            'unit_number' => 'S1',
            'rent_amount' => 45000,
            'deposit_amount' => 45000,
            'size' => 'Office Suite',
            'status' => UnitStatus::OCCUPIED,
        ]);

        $unitS2 = Unit::create([
            'property_id' => $businessHub->id,
            'unit_number' => 'S2',
            'rent_amount' => 50000,
            'deposit_amount' => 50000,
            'size' => 'Office Suite',
            'status' => UnitStatus::VACANT,
        ]);

        $unitS3 = Unit::create([
            'property_id' => $businessHub->id,
            'unit_number' => 'S3',
            'rent_amount' => 55000,
            'deposit_amount' => 55000,
            'size' => 'Large Office Suite',
            'status' => UnitStatus::VACANT,
        ]);

        $this->command->info('Created 10 units (5 occupied, 5 vacant)');

        // ────────────────────────────────────────────────────────────────
        // LEASES
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating leases...');

        $leaseData = [
            ['tenant' => $tenant1, 'unit' => $unitA1, 'rent' => 15000, 'months_ago' => 6],  // Grace → A1
            ['tenant' => $tenant2, 'unit' => $unitA2, 'rent' => 18000, 'months_ago' => 3],  // David → A2
            ['tenant' => $tenant3, 'unit' => $unitB1, 'rent' => 22000, 'months_ago' => 2],  // Sarah → B1
            ['tenant' => $tenant4, 'unit' => $unitV1, 'rent' => 35000, 'months_ago' => 4],  // James → V1
            ['tenant' => $tenant5, 'unit' => $unitS1, 'rent' => 45000, 'months_ago' => 5],  // Lucy → S1
        ];

        $leases = [];
        foreach ($leaseData as $data) {
            $startDate = Carbon::now()->subMonths($data['months_ago'])->startOfMonth();
            $endDate = $startDate->copy()->addYear();

            $lease = Lease::create([
                'tenant_id' => $data['tenant']->id,
                'unit_id' => $data['unit']->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rent_amount' => $data['rent'],
                'deposit' => $data['rent'],
                'terms' => 'Standard 12-month lease agreement. Rent is due on or before the 5th of each month. A late fee of 5% applies after the due date.',
                'status' => LeaseStatus::ACTIVE,
                'signed_at' => $startDate,
            ]);

            $leases[] = [
                'lease' => $lease,
                'months_ago' => $data['months_ago'],
                'rent' => $data['rent'],
            ];
        }

        // Add one PENDING lease for demo tenant (Grace) to showcase e-signature flow
        Lease::create([
            'tenant_id' => $tenant1->id,
            'unit_id' => $unitV2->id,
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->startOfMonth()->addYear(),
            'rent_amount' => 35000,
            'deposit' => 35000,
            'terms' => 'Standard 12-month lease agreement. Rent is due on or before the 5th of each month. A late fee of 5% applies after the due date. Tenant agrees to maintain the property in good condition.',
            'status' => LeaseStatus::PENDING,
        ]);

        $this->command->info('Created 5 active leases + 1 pending (for e-signature demo)');

        // ────────────────────────────────────────────────────────────────
        // INVOICES & PAYMENTS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating invoices and payments...');

        $invoiceCount = 0;
        $paymentCount = 0;

        foreach ($leases as $leaseInfo) {
            $lease = $leaseInfo['lease'];
            $monthsAgo = $leaseInfo['months_ago'];
            $rent = $leaseInfo['rent'];

            // Generate invoices for the past months (up to 3, but no more than months_ago)
            $invoiceMonths = min(3, $monthsAgo);

            for ($i = $invoiceMonths - 1; $i >= 0; $i--) {
                $dueDate = Carbon::now()->subMonths($i)->startOfMonth()->addDays(4); // Due on 5th
                $isCurrentMonth = ($i === 0);
                $monthLabel = Carbon::now()->subMonths($i)->format('F Y');

                $invoice = Invoice::create([
                    'lease_id' => $lease->id,
                    'amount' => $rent,
                    'due_date' => $dueDate,
                    'status' => $isCurrentMonth ? InvoiceStatus::PENDING : InvoiceStatus::PAID,
                    'description' => "Rent for {$monthLabel}",
                ]);

                $invoiceCount++;

                // Create payment for past (paid) invoices
                if (! $isCurrentMonth) {
                    $paidDate = $dueDate->copy()->subDays(rand(1, 4)); // Paid 1-4 days before due date
                    $receiptCode = 'S' . strtoupper(Str::random(9));

                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $rent,
                        'method' => PaymentMethod::MPESA,
                        'reference' => 'PAY-' . strtoupper(Str::random(8)),
                        'status' => PaymentStatus::COMPLETED,
                        'paid_at' => $paidDate,
                        'mpesa_receipt' => $receiptCode,
                    ]);

                    $paymentCount++;
                }
            }
        }

        $this->command->info("Created {$invoiceCount} invoices and {$paymentCount} payments");

        // ────────────────────────────────────────────────────────────────
        // MAINTENANCE REQUESTS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating maintenance requests...');

        MaintenanceRequest::create([
            'unit_id' => $unitA1->id,
            'tenant_id' => $tenant1->id,
            'title' => 'Leaking Kitchen Faucet',
            'description' => 'The kitchen faucet has been leaking steadily for the past two days. Water is pooling under the sink and I am concerned about water damage to the cabinet.',
            'priority' => MaintenancePriority::MEDIUM,
            'status' => MaintenanceStatus::PENDING,
        ]);

        MaintenanceRequest::create([
            'unit_id' => $unitA2->id,
            'tenant_id' => $tenant2->id,
            'title' => 'Broken Window Lock',
            'description' => 'The lock on the bedroom window is broken and the window cannot be secured. This is a security concern, especially at night.',
            'priority' => MaintenancePriority::HIGH,
            'status' => MaintenanceStatus::IN_PROGRESS,
            'assigned_to' => 'Kamau Repairs Ltd',
        ]);

        MaintenanceRequest::create([
            'unit_id' => $unitB1->id,
            'tenant_id' => $tenant3->id,
            'title' => 'Air Conditioning Not Working',
            'description' => 'The air conditioning unit stopped working yesterday. It turns on but only blows warm air. The filter was cleaned last month.',
            'priority' => MaintenancePriority::URGENT,
            'status' => MaintenanceStatus::PENDING,
        ]);

        $this->command->info('Created 3 maintenance requests');

        // ────────────────────────────────────────────────────────────────
        // NOTIFICATIONS
        // ────────────────────────────────────────────────────────────────
        $this->command->info('Creating notifications...');

        $notificationCount = 0;

        // Agent notifications
        $agentNotifications = [
            [
                'type' => NotificationType::MAINTENANCE_UPDATE,
                'subject' => 'New Maintenance Request',
                'message' => 'Grace Muthoni has submitted a maintenance request for Unit A1 at Sunrise Apartments: "Leaking Kitchen Faucet".',
                'sent_at' => now()->subDays(1),
            ],
            [
                'type' => NotificationType::INFO,
                'subject' => 'Monthly Collection Summary',
                'message' => 'Rent collection for January 2026 is at 80%. 4 out of 5 tenants have paid. 1 invoice is still pending.',
                'sent_at' => now()->subDays(3),
                'read_at' => now()->subDays(2),
            ],
            [
                'type' => NotificationType::WARNING,
                'subject' => 'Urgent Maintenance Request',
                'message' => 'Sarah Akinyi has reported an urgent issue at Unit B1, Sunrise Apartments: "Air Conditioning Not Working".',
                'sent_at' => now()->subHours(6),
            ],
        ];

        foreach ($agentNotifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $agent->id]));
            $notificationCount++;
        }

        // Landlord 1 (Mary) notifications
        $landlord1Notifications = [
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Payment Received',
                'message' => 'Rent payment of KES 15,000 has been received from Grace Muthoni for Unit A1 at Sunrise Apartments.',
                'sent_at' => now()->subDays(5),
                'read_at' => now()->subDays(4),
            ],
            [
                'type' => NotificationType::MAINTENANCE_UPDATE,
                'subject' => 'Maintenance Request Update',
                'message' => 'The broken window lock at Unit A2 has been assigned to Kamau Repairs Ltd. Status: In Progress.',
                'sent_at' => now()->subDays(2),
                'read_at' => now()->subDays(1),
            ],
        ];

        foreach ($landlord1Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $landlordUser1->id]));
            $notificationCount++;
        }

        // Landlord 2 (Peter) notifications
        $landlord2Notifications = [
            [
                'type' => NotificationType::SUCCESS,
                'subject' => 'New Lease Signed',
                'message' => 'Lucy Njeri has signed a lease for Unit S1 at Business Hub Tower. Lease term: 12 months starting from the 1st.',
                'sent_at' => now()->subDays(7),
                'read_at' => now()->subDays(6),
            ],
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Payment Received',
                'message' => 'Rent payment of KES 45,000 has been received from Lucy Njeri for Unit S1 at Business Hub Tower.',
                'sent_at' => now()->subDays(4),
            ],
        ];

        foreach ($landlord2Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $landlordUser2->id]));
            $notificationCount++;
        }

        // Tenant 1 (Grace) notifications
        $tenant1Notifications = [
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Due Reminder',
                'message' => 'Your rent of KES 15,000 for Unit A1, Sunrise Apartments is due on the 5th. Please ensure timely payment to avoid late fees.',
                'sent_at' => now()->startOfMonth(),
            ],
            [
                'type' => NotificationType::MAINTENANCE_UPDATE,
                'subject' => 'Maintenance Request Received',
                'message' => 'Your maintenance request "Leaking Kitchen Faucet" has been received and is pending review by the property manager.',
                'sent_at' => now()->subDays(1),
                'read_at' => now()->subHours(12),
            ],
        ];

        foreach ($tenant1Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $tenantUser1->id]));
            $notificationCount++;
        }

        // Tenant 2 (David) notifications
        $tenant2Notifications = [
            [
                'type' => NotificationType::MAINTENANCE_UPDATE,
                'subject' => 'Maintenance In Progress',
                'message' => 'Your maintenance request "Broken Window Lock" is now being handled by Kamau Repairs Ltd. Expected completion within 2 business days.',
                'sent_at' => now()->subDays(1),
                'read_at' => now()->subHours(18),
            ],
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Due Reminder',
                'message' => 'Your rent of KES 18,000 for Unit A2, Sunrise Apartments is due on the 5th. Please ensure timely payment.',
                'sent_at' => now()->startOfMonth(),
            ],
        ];

        foreach ($tenant2Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $tenantUser2->id]));
            $notificationCount++;
        }

        // Tenant 3 (Sarah) notifications
        $tenant3Notifications = [
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Due Reminder',
                'message' => 'Your rent of KES 22,000 for Unit B1, Sunrise Apartments is due on the 5th. Please ensure timely payment.',
                'sent_at' => now()->startOfMonth(),
            ],
            [
                'type' => NotificationType::MAINTENANCE_UPDATE,
                'subject' => 'Maintenance Request Received',
                'message' => 'Your maintenance request "Air Conditioning Not Working" has been received. Due to the urgent priority, it will be reviewed promptly.',
                'sent_at' => now()->subHours(6),
            ],
            [
                'type' => NotificationType::LEASE_EXPIRY,
                'subject' => 'Lease Renewal Reminder',
                'message' => 'Your lease for Unit B1 at Sunrise Apartments will expire in 10 months. Contact your agent to discuss renewal options.',
                'sent_at' => now()->subDays(10),
                'read_at' => now()->subDays(8),
            ],
        ];

        foreach ($tenant3Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $tenantUser3->id]));
            $notificationCount++;
        }

        // Tenant 4 (James) notifications
        $tenant4Notifications = [
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Due Reminder',
                'message' => 'Your rent of KES 35,000 for Unit V1, Garden Villas is due on the 5th. Please ensure timely payment.',
                'sent_at' => now()->startOfMonth(),
            ],
            [
                'type' => NotificationType::SUCCESS,
                'subject' => 'Payment Confirmed',
                'message' => 'Your rent payment of KES 35,000 for January has been received and confirmed. Thank you for your prompt payment.',
                'sent_at' => now()->subMonth()->addDays(3),
                'read_at' => now()->subMonth()->addDays(3),
            ],
        ];

        foreach ($tenant4Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $tenantUser4->id]));
            $notificationCount++;
        }

        // Tenant 5 (Lucy) notifications
        $tenant5Notifications = [
            [
                'type' => NotificationType::PAYMENT_REMINDER,
                'subject' => 'Rent Due Reminder',
                'message' => 'Your rent of KES 45,000 for Unit S1, Business Hub Tower is due on the 5th. Please ensure timely payment.',
                'sent_at' => now()->startOfMonth(),
            ],
            [
                'type' => NotificationType::INFO,
                'subject' => 'Welcome to Business Hub Tower',
                'message' => 'Welcome to your new office space at Business Hub Tower. Please contact the property manager if you have any questions about the facility.',
                'sent_at' => now()->subMonths(5),
                'read_at' => now()->subMonths(5),
            ],
        ];

        foreach ($tenant5Notifications as $data) {
            Notification::create(array_merge($data, ['user_id' => $tenantUser5->id]));
            $notificationCount++;
        }

        $this->command->info("Created {$notificationCount} notifications");

        // ────────────────────────────────────────────────────────────────
        // SUMMARY
        // ────────────────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('=== Seeding Complete ===');
        $this->command->info('Users:                8 (1 agent, 2 landlords, 5 tenants)');
        $this->command->info('Properties:           3');
        $this->command->info('Units:                10 (5 occupied, 5 vacant)');
        $this->command->info('Leases:               5 (all active)');
        $this->command->info("Invoices:             {$invoiceCount}");
        $this->command->info("Payments:             {$paymentCount}");
        $this->command->info('Maintenance Requests: 3');
        $this->command->info("Notifications:        {$notificationCount}");
        $this->command->info('');
        $this->command->info('Demo Logins:');
        $this->command->info('  Agent:    agent@rentify.co.ke / password');
        $this->command->info('  Landlord: landlord@rentify.co.ke / password');
        $this->command->info('  Tenant:   tenant@rentify.co.ke / password');
    }
}
