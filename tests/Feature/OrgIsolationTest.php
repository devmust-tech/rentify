<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\LeaseStatus;
use App\Enums\OrganizationStatus;
use App\Models\Invoice;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\Organization;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\WithOrganization;
use Tests\TestCase;

/**
 * Verifies that the BelongsToOrganization global scope prevents
 * users of one organization from accessing another org's data.
 */
class OrgIsolationTest extends TestCase
{
    use RefreshDatabase, WithOrganization;

    private Organization $orgB;
    private User $agentB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpOrganization(); // sets up $this->org and $this->agent

        // Create a second independent organization
        $this->orgB = Organization::create([
            'name'          => 'Org B',
            'slug'          => 'org-b',
            'status'        => OrganizationStatus::ACTIVE,
            'primary_color' => '#000000',
            'accent_color'  => '#ffffff',
            'plan'          => 'pro',
            'features'      => null,
        ]);

        $this->agentB = User::factory()->agent()->create(['organization_id' => $this->orgB->id]);
    }

    private function seedInvoiceForOrg(Organization $org, User $agentUser): Invoice
    {
        // Bind org so BelongsToOrganization scope uses it for creates
        app()->instance('currentOrganization', $org);

        $landlordUser = User::factory()->landlord()->create(['organization_id' => $org->id]);
        $landlord     = Landlord::create(['user_id' => $landlordUser->id, 'organization_id' => $org->id, 'national_id' => '55667788']);

        $property = Property::create([
            'organization_id' => $org->id,
            'landlord_id'     => $landlord->id,
            'agent_id'        => $agentUser->id,
            'name'            => 'Isolation Test Property',
            'property_type'   => 'apartment',
            'address'         => '1 Isolation Rd',
            'county'          => '047',
            'city'            => 'Nairobi',
        ]);

        $unit = Unit::create([
            'organization_id' => $org->id,
            'property_id'     => $property->id,
            'unit_number'     => 'C1',
            'rent_amount'     => 20000,
        ]);

        $tenantUser = User::factory()->tenant()->create(['organization_id' => $org->id]);
        $tenant     = Tenant::create(['organization_id' => $org->id, 'user_id' => $tenantUser->id, 'phone' => '+254700000001']);

        $lease = Lease::create([
            'organization_id' => $org->id,
            'tenant_id'       => $tenant->id,
            'unit_id'         => $unit->id,
            'start_date'      => now()->subMonth(),
            'end_date'        => now()->addYear(),
            'rent_amount'     => 20000,
            'deposit_amount'  => 20000,
            'status'          => LeaseStatus::ACTIVE,
        ]);

        $invoice = Invoice::create([
            'organization_id' => $org->id,
            'lease_id'        => $lease->id,
            'invoice_number'  => 'INV-' . now()->year . '-0001',
            'amount'          => 20000,
            'due_date'        => now()->addDays(5),
            'period_start'    => now()->startOfMonth(),
            'period_end'      => now()->endOfMonth(),
            'status'          => InvoiceStatus::PENDING,
        ]);

        return $invoice;
    }

    public function test_agent_cannot_view_another_orgs_invoice(): void
    {
        // Create invoice for org B
        $invoiceB = $this->seedInvoiceForOrg($this->orgB, $this->agentB);

        // Switch context to org A and attempt to view org B's invoice
        app()->instance('currentOrganization', $this->org);

        $this->actingAsAgent()
            ->get(route('agent.invoices.show', $invoiceB->id))
            ->assertStatus(404);
    }

    public function test_agent_index_only_shows_own_org_invoices(): void
    {
        $invoiceA = $this->seedInvoiceForOrg($this->org, $this->agent);
        $invoiceB = $this->seedInvoiceForOrg($this->orgB, $this->agentB);

        // Switch context back to org A — the global scope should only return org A's invoices
        app()->instance('currentOrganization', $this->org);

        // Database-level: org scope should only see 1 invoice (org A's)
        $this->assertSame(1, Invoice::count());
        $this->assertSame($invoiceA->id, Invoice::first()->id);

        // Org B's invoice exists in total DB but not in org A's scope
        $this->assertSame(2, Invoice::withoutGlobalScopes()->count());
    }

    public function test_properties_are_scoped_to_org(): void
    {
        $this->seedInvoiceForOrg($this->org, $this->agent);
        $this->seedInvoiceForOrg($this->orgB, $this->agentB);

        app()->instance('currentOrganization', $this->org);

        // Only 1 property should exist for org A
        $orgAProperties = Property::count();
        $this->assertSame(1, $orgAProperties);

        // Without scope, there are 2
        $allProperties = Property::withoutGlobalScopes()->count();
        $this->assertSame(2, $allProperties);
    }
}
