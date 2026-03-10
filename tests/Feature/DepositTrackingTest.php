<?php

namespace Tests\Feature;

use App\Enums\LeaseStatus;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\WithOrganization;
use Tests\TestCase;

class DepositTrackingTest extends TestCase
{
    use RefreshDatabase, WithOrganization;

    private Lease $lease;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpOrganization();
        $this->lease = $this->makeLease();
    }

    private function makeLease(): Lease
    {
        $landlordUser = User::factory()->landlord()->create(['organization_id' => $this->org->id]);
        $landlord     = Landlord::create(['user_id' => $landlordUser->id, 'organization_id' => $this->org->id, 'national_id' => '11223344']);

        $property = Property::create([
            'organization_id' => $this->org->id,
            'landlord_id'     => $landlord->id,
            'agent_id'        => $this->agent->id,
            'name'            => 'Deposit Test Property',
            'property_type'   => 'apartment',
            'address'         => '10 Deposit Lane',
            'county'          => '047',
            'city'            => 'Nairobi',
        ]);

        $unit = Unit::create([
            'organization_id' => $this->org->id,
            'property_id'     => $property->id,
            'unit_number'     => 'D1',
            'rent_amount'     => 25000,
        ]);

        $tenantUser = User::factory()->tenant()->create(['organization_id' => $this->org->id]);
        $tenant     = Tenant::create(['organization_id' => $this->org->id, 'user_id' => $tenantUser->id, 'phone' => '+254700000001']);

        return Lease::create([
            'organization_id' => $this->org->id,
            'tenant_id'       => $tenant->id,
            'unit_id'         => $unit->id,
            'start_date'      => now()->subMonth(),
            'end_date'        => now()->addYear(),
            'rent_amount'     => 25000,
            'deposit_amount'  => 25000,
            'status'          => LeaseStatus::ACTIVE,
        ]);
    }

    public function test_deposit_status_is_unpaid_initially(): void
    {
        $this->assertNull($this->lease->deposit_paid_at);
        $this->assertNull($this->lease->deposit_refunded_at);
        $this->assertSame('unpaid', $this->lease->depositStatus());
    }

    public function test_mark_deposit_paid_sets_timestamp(): void
    {
        $this->actingAsAgent()
            ->post(route('agent.leases.deposit.paid', $this->lease))
            ->assertRedirect();

        $this->lease->refresh();
        $this->assertNotNull($this->lease->deposit_paid_at);
        $this->assertSame('paid', $this->lease->depositStatus());
    }

    public function test_mark_deposit_refunded_sets_timestamp(): void
    {
        // Must be paid first
        $this->lease->update(['deposit_paid_at' => now()]);

        $this->actingAsAgent()
            ->post(route('agent.leases.deposit.refunded', $this->lease));

        $this->lease->refresh();
        $this->assertNotNull($this->lease->deposit_refunded_at);
        $this->assertSame('refunded', $this->lease->depositStatus());
    }

    public function test_cannot_mark_refunded_before_paid(): void
    {
        // deposit_paid_at is null — should redirect back without setting refunded
        $this->actingAsAgent()
            ->post(route('agent.leases.deposit.refunded', $this->lease))
            ->assertRedirect();

        $this->lease->refresh();
        $this->assertNull($this->lease->deposit_refunded_at);
    }

    public function test_agent_from_different_org_cannot_update_deposit(): void
    {
        $otherOrg = \App\Models\Organization::create([
            'name'          => 'Other Org',
            'slug'          => 'other-org',
            'status'        => \App\Enums\OrganizationStatus::ACTIVE,
            'primary_color' => '#000',
            'accent_color'  => '#fff',
        ]);
        $otherAgent = User::factory()->agent()->create(['organization_id' => $otherOrg->id]);

        // Bind the other org as current so middleware is happy, but the lease belongs to $this->org
        app()->instance('currentOrganization', $otherOrg);

        $this->actingAs($otherAgent)
            ->post(route('agent.leases.deposit.paid', $this->lease))
            ->assertStatus(404);

        $this->lease->refresh();
        $this->assertNull($this->lease->deposit_paid_at);
    }
}
