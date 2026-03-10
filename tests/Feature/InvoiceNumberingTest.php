<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\LeaseStatus;
use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\WithOrganization;
use Tests\TestCase;

class InvoiceNumberingTest extends TestCase
{
    use RefreshDatabase, WithOrganization;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpOrganization();
    }

    private function makeLease(): Lease
    {
        $landlordUser = User::factory()->landlord()->create(['organization_id' => $this->org->id]);
        $landlord     = Landlord::create(['user_id' => $landlordUser->id, 'organization_id' => $this->org->id, 'national_id' => '12345678']);

        $property = Property::create([
            'organization_id' => $this->org->id,
            'landlord_id'     => $landlord->id,
            'agent_id'        => $this->agent->id,
            'name'            => 'Test Property',
            'property_type'   => 'apartment',
            'address'         => '123 Test St',
            'county'          => '047',
            'city'            => 'Nairobi',
        ]);

        $unit = Unit::create([
            'organization_id' => $this->org->id,
            'property_id'     => $property->id,
            'unit_number'     => 'A1',
            'rent_amount'     => 20000,
        ]);

        $tenantUser = User::factory()->tenant()->create(['organization_id' => $this->org->id]);
        $tenant     = Tenant::create(['organization_id' => $this->org->id, 'user_id' => $tenantUser->id, 'phone' => '+254700000001']);

        return Lease::create([
            'organization_id' => $this->org->id,
            'tenant_id'       => $tenant->id,
            'unit_id'         => $unit->id,
            'start_date'      => now()->subMonth(),
            'end_date'        => now()->addYear(),
            'rent_amount'     => 20000,
            'deposit_amount'  => 20000,
            'status'          => LeaseStatus::ACTIVE,
        ]);
    }

    public function test_first_invoice_gets_number_inv_year_0001(): void
    {
        $lease = $this->makeLease();
        $year  = now()->year;

        $this->actingAsAgent()
            ->post(route('agent.invoices.store'), [
                'lease_id'    => $lease->id,
                'amount'      => 20000,
                'due_date'    => now()->addDays(5)->format('Y-m-d'),
                'description' => 'Rent',
            ]);

        $invoice = Invoice::first();
        $this->assertNotNull($invoice);
        $this->assertSame("INV-{$year}-0001", $invoice->invoice_number);
    }

    public function test_sequential_invoices_increment_number(): void
    {
        $lease = $this->makeLease();
        $year  = now()->year;

        // Seed an existing invoice with known number so controller logic increments it
        Invoice::create([
            'organization_id' => $this->org->id,
            'lease_id'        => $lease->id,
            'invoice_number'  => "INV-{$year}-0003",
            'amount'          => 20000,
            'due_date'        => now()->subDays(10),
            'period_start'    => now()->subMonth()->startOfMonth(),
            'period_end'      => now()->subMonth()->endOfMonth(),
            'status'          => InvoiceStatus::PENDING,
        ]);

        $this->actingAsAgent()
            ->post(route('agent.invoices.store'), [
                'lease_id'    => $lease->id,
                'amount'      => 20000,
                'due_date'    => now()->addDays(5)->format('Y-m-d'),
                'description' => 'Rent',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        // Two invoices should exist: the seeded 0003 and the new 0004
        $this->assertSame(2, Invoice::count());
        $this->assertTrue(
            Invoice::where('invoice_number', "INV-{$year}-0004")->exists(),
            'Expected INV-' . $year . '-0004 to exist. Numbers: ' .
            Invoice::pluck('invoice_number')->implode(', ')
        );
    }

    public function test_invoice_numbers_are_scoped_to_org(): void
    {
        // Org B has 5 invoices — org A's count should start from 1
        $orgB = \App\Models\Organization::create([
            'name'          => 'Org B',
            'slug'          => 'org-b',
            'status'        => \App\Enums\OrganizationStatus::ACTIVE,
            'primary_color' => '#000',
            'accent_color'  => '#fff',
        ]);

        $leaseB = $this->makeLease(); // belongs to $this->org (org A)

        // Manually seed 5 invoices for org B
        for ($i = 1; $i <= 5; $i++) {
            Invoice::withoutGlobalScopes()->create([
                'organization_id' => $orgB->id,
                'lease_id'        => $leaseB->id,
                'invoice_number'  => 'INV-' . now()->year . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'amount'          => 10000,
                'due_date'        => now(),
                'period_start'    => now()->startOfMonth(),
                'period_end'      => now()->endOfMonth(),
                'status'          => InvoiceStatus::PENDING,
            ]);
        }

        // Create invoice for org A — should be 0001, not 0006
        $lease = $this->makeLease();
        $this->actingAsAgent()
            ->post(route('agent.invoices.store'), [
                'lease_id'    => $lease->id,
                'amount'      => 20000,
                'due_date'    => now()->addDays(5)->format('Y-m-d'),
                'description' => 'Rent',
            ]);

        $invoice = Invoice::orderByDesc('created_at')->first();
        $this->assertSame('INV-' . now()->year . '-0001', $invoice->invoice_number);
    }
}
