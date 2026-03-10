<?php

namespace Tests\Feature\Concerns;

use App\Enums\OrganizationStatus;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\URL;

/**
 * Helper trait for feature tests that need a live Organization context.
 *
 * Sets up:
 *  - $this->org   — an active Organization
 *  - $this->agent — a User with AGENT role belonging to the org
 *
 * Also:
 *  - Binds 'currentOrganization' in the container (required by BelongsToOrganization scope)
 *  - Sets URL::defaults(['org' => $slug]) so route() helpers generate correct subdomain URLs
 *  - Overrides HTTP_HOST server variable so subdomain routing resolves correctly
 */
trait WithOrganization
{
    protected Organization $org;
    protected User $agent;

    protected function setUpOrganization(array $orgAttributes = []): void
    {
        $this->org = Organization::create(array_merge([
            'name'          => 'Test Org',
            'slug'          => 'test-org',
            'status'        => OrganizationStatus::ACTIVE,
            'primary_color' => '#4f46e5',
            'accent_color'  => '#818cf8',
            'plan'          => 'pro',
            'features'      => null, // null = all features enabled
        ], $orgAttributes));

        $this->agent = User::factory()->agent()->create([
            'organization_id' => $this->org->id,
        ]);

        // Bind org globally so BelongsToOrganization scope fires correctly on creates
        app()->instance('currentOrganization', $this->org);

        // Set URL defaults so route() helpers include the {org} parameter
        URL::defaults(['org' => $this->org->slug]);

        // Override HTTP_HOST for subdomain routing in test requests
        $domain = config('app.domain', 'localhost');
        $this->serverVariables = array_merge(
            $this->serverVariables ?? [],
            ['HTTP_HOST' => $this->org->slug . '.' . $domain]
        );
    }

    protected function actingAsAgent(): static
    {
        return $this->actingAs($this->agent);
    }
}
