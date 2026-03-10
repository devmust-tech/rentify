<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Mail\TenantInvitation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Tenant::with(['user', 'activeLease.unit.property'])
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
            ))
            ->paginate(15)->withQueryString();
        return view('agent.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('agent.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'national_id' => 'nullable|string|max:50',
            'kra_pin' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_phone' => 'nullable|string|max:20',
            'guarantor_relationship' => 'nullable|string|max:50',
            'guarantor_id' => 'nullable|string|max:50',
            'occupants' => 'nullable|integer|min:1|max:20',
            'children' => 'nullable|integer|min:0|max:20',
            'has_pets' => 'nullable|boolean',
            'pet_details' => 'nullable|string|max:255',
            'preferred_contact' => 'nullable|string|in:phone,whatsapp,email,sms',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt(Str::random(32)),
            'role' => UserRole::TENANT,
            'status' => UserStatus::INACTIVE,
        ]);

        $idDocPath = null;
        if ($request->hasFile('id_document')) {
            $idDocPath = $request->file('id_document')->store('tenant-documents', 'public');
        }

        $emergencyContact = null;
        if ($request->emergency_contact_name) {
            $emergencyContact = json_encode([
                'name' => $request->emergency_contact_name,
                'phone' => $request->emergency_contact_phone,
                'relationship' => $request->emergency_contact_relationship,
            ]);
        }

        $tenant = Tenant::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'emergency_contact' => $emergencyContact,
            'id_document' => $idDocPath,
            'national_id' => $request->national_id,
            'kra_pin' => $request->kra_pin,
            'occupation' => $request->occupation,
            'employer' => $request->employer,
            'monthly_income' => $request->monthly_income,
            'guarantor_name' => $request->guarantor_name,
            'guarantor_phone' => $request->guarantor_phone,
            'guarantor_relationship' => $request->guarantor_relationship,
            'guarantor_id' => $request->guarantor_id,
            'occupants' => $request->occupants,
            'children' => $request->children,
            'has_pets' => $request->boolean('has_pets'),
            'pet_details' => $request->pet_details,
            'preferred_contact' => $request->preferred_contact ?? 'phone',
        ]);

        $this->sendInvite($request, $request->route('org'), $tenant);

        return redirect()->route('agent.tenants.index')
            ->with('success', 'Tenant created. An invitation email has been sent to ' . $user->email . '.');
    }

    public function show(string $org, Tenant $tenant)
    {
        $tenant->load(['user', 'leases.unit.property', 'maintenanceRequests.unit']);
        return view('agent.tenants.show', compact('tenant'));
    }

    public function edit(string $org, Tenant $tenant)
    {
        $tenant->load('user');
        return view('agent.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, string $org, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $tenant->user_id,
            'phone' => 'required|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'national_id' => 'nullable|string|max:50',
            'kra_pin' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_phone' => 'nullable|string|max:20',
            'guarantor_relationship' => 'nullable|string|max:50',
            'guarantor_id' => 'nullable|string|max:50',
            'occupants' => 'nullable|integer|min:1|max:20',
            'children' => 'nullable|integer|min:0|max:20',
            'has_pets' => 'nullable|boolean',
            'pet_details' => 'nullable|string|max:255',
            'preferred_contact' => 'nullable|string|in:phone,whatsapp,email,sms',
        ]);

        $tenant->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $emergencyContact = null;
        if ($request->emergency_contact_name) {
            $emergencyContact = json_encode([
                'name' => $request->emergency_contact_name,
                'phone' => $request->emergency_contact_phone,
                'relationship' => $request->emergency_contact_relationship,
            ]);
        }

        $data = [
            'phone' => $request->phone,
            'emergency_contact' => $emergencyContact,
            'national_id' => $request->national_id,
            'kra_pin' => $request->kra_pin,
            'occupation' => $request->occupation,
            'employer' => $request->employer,
            'monthly_income' => $request->monthly_income,
            'guarantor_name' => $request->guarantor_name,
            'guarantor_phone' => $request->guarantor_phone,
            'guarantor_relationship' => $request->guarantor_relationship,
            'guarantor_id' => $request->guarantor_id,
            'occupants' => $request->occupants,
            'children' => $request->children,
            'has_pets' => $request->boolean('has_pets'),
            'pet_details' => $request->pet_details,
            'preferred_contact' => $request->preferred_contact ?? 'phone',
        ];

        if ($request->hasFile('id_document')) {
            $data['id_document'] = $request->file('id_document')->store('tenant-documents', 'public');
        }

        $tenant->update($data);

        return redirect()->route('agent.tenants.show', $tenant)->with('success', 'Tenant updated.');
    }

    public function destroy(string $org, Tenant $tenant)
    {
        $tenant->user->delete();
        $tenant->delete();
        return redirect()->route('agent.tenants.index')->with('success', 'Tenant deleted.');
    }

    public function bulkDelete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'string']);

        $tenants = Tenant::whereIn('id', $request->ids)->with('user')->get();

        foreach ($tenants as $tenant) {
            $tenant->user?->delete();
            $tenant->delete();
        }

        return redirect()->route('agent.tenants.index')
            ->with('success', $tenants->count() . ' ' . \Illuminate\Support\Str::plural('tenant', $tenants->count()) . ' deleted.');
    }

    /**
     * Send (or resend) an invitation email so the tenant can set their own password.
     */
    public function sendInvite(Request $request, string $org, Tenant $tenant)
    {
        $org = app('currentOrganization');

        $inviteUrl = URL::temporarySignedRoute(
            'tenant.invitation.show',
            now()->addHours(72),
            ['tenant' => $tenant->id]
        );

        Mail::to($tenant->user->email)->queue(
            new TenantInvitation($tenant, $inviteUrl, $org->name)
        );

        return back()->with('success', "Invitation sent to {$tenant->user->email}.");
    }
}
