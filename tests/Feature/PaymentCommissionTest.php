<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\LeaseStatus;
use App\Enums\OrganizationStatus;
use App\Models\Invoice;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\WithOrganization;
use Tests\TestCase;

class PaymentCommissionTest extends TestCase
{
    use RefreshDatabase, WithOrganization;

    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpOrganization(['settings' => ['commission_rate' => 10]]);
        $this->invoice = $this->makeInvoice();
    }

    private function makeInvoice(): Invoice
    {
        $landlordUser = User::factory()->landlord()->create(['organization_id' => $this->org->id]);
        $landlord     = Landlord::create(['user_id' => $landlordUser->id, 'organization_id' => $this->org->id, 'national_id' => '99887766']);

        $property = Property::create([
            'organization_id' => $this->org->id,
            'landlord_id'     => $landlord->id,
            'agent_id'        => $this->agent->id,
            'name'            => 'Commission Test Property',
            'property_type'   => 'apartment',
            'address'         => '1 Commission Ave',
            'county'          => '047',
            'city'            => 'Nairobi',
        ]);

        $unit = Unit::create([
            'organization_id' => $this->org->id,
            'property_id'     => $property->id,
            'unit_number'     => 'B1',
            'rent_amount'     => 30000,
        ]);

        $tenantUser = User::factory()->tenant()->create(['organization_id' => $this->org->id]);
        $tenant     = Tenant::create(['organization_id' => $this->org->id, 'user_id' => $tenantUser->id, 'phone' => '+254700000001']);

        $lease = Lease::create([
            'organization_id' => $this->org->id,
            'tenant_id'       => $tenant->id,
            'unit_id'         => $unit->id,
            'start_date'      => now()->subMonth(),
            'end_date'        => now()->addYear(),
            'rent_amount'     => 30000,
            'deposit_amount'  => 30000,
            'status'          => LeaseStatus::ACTIVE,
        ]);

        return Invoice::create([
            'organization_id' => $this->org->id,
            'lease_id'        => $lease->id,
            'invoice_number'  => 'INV-' . now()->year . '-0001',
            'amount'          => 30000,
            'due_date'        => now()->addDays(5),
            'period_start'    => now()->startOfMonth(),
            'period_end'      => now()->endOfMonth(),
            'status'          => InvoiceStatus::PENDING,
        ]);
    }

    public function test_payment_calculates_commission_from_org_settings(): void
    {
        // Org commission_rate = 10%, payment = 30000 → commission = 3000
        $this->actingAsAgent()
            ->post(route('agent.payments.store'), [
                'invoice_id' => $this->invoice->id,
                'amount'     => 30000,
                'method'     => 'cash',
                'reference'  => 'CASH-001',
            ]);

        $payment = Payment::first();
        $this->assertNotNull($payment);
        $this->assertEquals(3000.00, (float) $payment->commission_amount);
    }

    public function test_zero_commission_rate_stores_null(): void
    {
        // Override org with 0% commission
        $this->org->update(['settings' => ['commission_rate' => 0]]);

        $this->actingAsAgent()
            ->post(route('agent.payments.store'), [
                'invoice_id' => $this->invoice->id,
                'amount'     => 30000,
                'method'     => 'cash',
                'reference'  => 'CASH-002',
            ]);

        $payment = Payment::first();
        $this->assertNull($payment->commission_amount);
    }

    public function test_commission_is_proportional_to_partial_payment(): void
    {
        // 10% of 15000 = 1500
        $this->actingAsAgent()
            ->post(route('agent.payments.store'), [
                'invoice_id' => $this->invoice->id,
                'amount'     => 15000,
                'method'     => 'cash',
                'reference'  => 'CASH-003',
            ]);

        $payment = Payment::first();
        $this->assertEquals(1500.00, (float) $payment->commission_amount);
    }

    public function test_payment_marks_invoice_as_paid_when_full_amount(): void
    {
        $this->actingAsAgent()
            ->post(route('agent.payments.store'), [
                'invoice_id' => $this->invoice->id,
                'amount'     => 30000,
                'method'     => 'cash',
                'reference'  => 'CASH-004',
            ]);

        $this->invoice->refresh();
        $this->assertSame('paid', $this->invoice->status->value);
    }
}
