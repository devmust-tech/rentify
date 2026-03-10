<?php

namespace Tests\Feature;

use App\Enums\OrganizationStatus;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\WithOrganization;
use Tests\TestCase;

/**
 * Verifies that the CheckFeature middleware blocks access to routes
 * when an organization's feature list does not include the required feature.
 */
class FeatureGateTest extends TestCase
{
    use RefreshDatabase, WithOrganization;

    protected function setUp(): void
    {
        parent::setUp();
        // Basic plan: only core features, no invoices/payments/reports
        $this->setUpOrganization([
            'plan'     => 'basic',
            'features' => ['properties', 'units', 'tenants', 'leases', 'maintenance', 'notifications'],
        ]);
    }

    public function test_invoices_index_forbidden_without_invoices_feature(): void
    {
        $this->actingAsAgent()
            ->get(route('agent.invoices.index'))
            ->assertStatus(403);
    }

    public function test_payments_index_forbidden_without_payments_feature(): void
    {
        $this->actingAsAgent()
            ->get(route('agent.payments.index'))
            ->assertStatus(403);
    }

    public function test_invoices_accessible_when_feature_enabled(): void
    {
        $this->org->update(['features' => ['properties', 'units', 'tenants', 'leases', 'maintenance', 'notifications', 'invoices', 'payments']]);

        $this->actingAsAgent()
            ->get(route('agent.invoices.index'))
            ->assertStatus(200);
    }

    public function test_null_features_means_all_features_allowed(): void
    {
        // null features = all features unlocked (enterprise behaviour)
        $this->org->update(['features' => null]);

        $this->actingAsAgent()
            ->get(route('agent.invoices.index'))
            ->assertStatus(200);

        $this->actingAsAgent()
            ->get(route('agent.payments.index'))
            ->assertStatus(200);
    }

    public function test_maintenance_accessible_on_basic_plan(): void
    {
        $this->actingAsAgent()
            ->get(route('agent.maintenance.index'))
            ->assertStatus(200);
    }
}
