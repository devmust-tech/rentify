<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrganizationStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Mail\OrganizationApproved;
use App\Models\Landlord;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withoutGlobalScopes()
            ->with('owner')
            ->withCount(['users' => fn($q) => $q->withoutGlobalScopes()])
            ->latest()
            ->paginate(20);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->loadCount(['users' => fn($q) => $q->withoutGlobalScopes()]);
        $organization->load('owner');

        $users = User::withoutGlobalScopes()
            ->where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();

        $activityLogs = \App\Models\ActivityLog::where('organization_id', $organization->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        // KPI metrics scoped to this org (bypass global scopes)
        $propertyIds = \App\Models\Property::withoutGlobalScopes()
            ->where('organization_id', $organization->id)->pluck('id');

        $unitIds = \App\Models\Unit::withoutGlobalScopes()
            ->whereIn('property_id', $propertyIds)->pluck('id');

        $leaseIds = \App\Models\Lease::withoutGlobalScopes()
            ->whereIn('unit_id', $unitIds)->pluck('id');

        $invoiceIds = \App\Models\Invoice::withoutGlobalScopes()
            ->whereIn('lease_id', $leaseIds)->pluck('id');

        $metrics = [
            'properties'          => $propertyIds->count(),
            'units'               => $unitIds->count(),
            'active_leases'       => \App\Models\Lease::withoutGlobalScopes()
                ->whereIn('unit_id', $unitIds)->where('status', 'active')->count(),
            'active_tenants'      => \App\Models\Lease::withoutGlobalScopes()
                ->whereIn('unit_id', $unitIds)->where('status', 'active')
                ->distinct('tenant_id')->count('tenant_id'),
            'total_revenue'       => \App\Models\Payment::withoutGlobalScopes()
                ->whereIn('invoice_id', $invoiceIds)->where('status', 'completed')->sum('amount'),
            'pending_maintenance' => \App\Models\MaintenanceRequest::withoutGlobalScopes()
                ->whereIn('unit_id', $unitIds)->whereIn('status', ['pending', 'in_progress'])->count(),
        ];

        return view('admin.organizations.show', compact('organization', 'users', 'activityLogs', 'metrics'));
    }

    public function approve(Organization $organization)
    {
        $organization->update(['status' => OrganizationStatus::ACTIVE]);

        // Activate the owner user and notify them
        if ($organization->owner_id) {
            User::withoutGlobalScopes()
                ->where('id', $organization->owner_id)
                ->update(['status' => UserStatus::ACTIVE]);

            $organization->load('owner');
            if ($organization->owner?->email) {
                Mail::to($organization->owner->email)
                    ->queue(new OrganizationApproved($organization));
            }
        }

        return back()->with('success', "Organization \"{$organization->name}\" has been approved.");
    }

    public function suspend(Organization $organization)
    {
        $organization->update(['status' => OrganizationStatus::SUSPENDED]);

        return back()->with('success', "Organization \"{$organization->name}\" has been suspended.");
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('admin.organizations.index')
            ->with('success', "Organization \"{$organization->name}\" has been deleted.");
    }

    public function updatePlan(Request $request, Organization $organization)
    {
        $request->validate([
            'plan'       => ['required', 'string', 'in:basic,pro,enterprise'],
            'features'   => ['sometimes', 'array'],
            'features.*' => ['string'],
        ]);

        $plan     = $request->input('plan');
        $features = $request->has('features')
            ? $request->input('features')
            : Organization::planFeatures($plan);

        $organization->update([
            'plan'     => $plan,
            'features' => $features,
        ]);

        return back()->with('success', "Plan updated to " . ucfirst($plan) . " with " . count($features) . " features.");
    }

    public function updateUserRole(Request $request, Organization $organization, User $user)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:agent,landlord,tenant'],
        ]);

        $newRole = UserRole::from($request->input('role'));
        $oldRole = $user->role;

        $user->update(['role' => $newRole]);

        // Create profile record if switching TO landlord
        if ($newRole === UserRole::LANDLORD && $oldRole !== UserRole::LANDLORD) {
            Landlord::firstOrCreate(['user_id' => $user->id], [
                'organization_id' => $organization->id,
            ]);
        }

        // Create profile record if switching TO tenant
        if ($newRole === UserRole::TENANT && $oldRole !== UserRole::TENANT) {
            Tenant::firstOrCreate(['user_id' => $user->id], [
                'organization_id' => $organization->id,
            ]);
        }

        app(ActivityLogger::class)->log(
            'user.role_changed',
            "{$user->name}'s role changed from {$oldRole->value} to {$newRole->value} in org {$organization->name}",
            $user,
            ['old_role' => $oldRole->value, 'new_role' => $newRole->value],
            $organization->id,
        );

        return back()->with('success', "{$user->name}'s role updated to " . $newRole->value . ".");
    }
}
