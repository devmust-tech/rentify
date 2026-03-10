<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Mail\TenantInvitation;
use App\Models\Lease;
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
        $landlordId = $request->user()->landlord->id;

        // Get all tenants with leases on landlord's properties
        $tenants = Tenant::whereHas('leases.unit.property', function ($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })
        ->with(['user', 'leases.unit.property'])
        ->paginate(15);

        return view('landlord.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('landlord.tenants.create');
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
        ]);

        // Auto-send invitation email
        $org = app('currentOrganization');
        $inviteUrl = URL::temporarySignedRoute(
            'tenant.invitation.show',
            now()->addHours(72),
            ['tenant' => $tenant->id]
        );
        Mail::to($user->email)->queue(new TenantInvitation($tenant, $inviteUrl, $org->name));

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Tenant created. An invitation email has been sent to ' . $user->email . '.');
    }

    public function show(Request $request, string $org, Tenant $tenant)
    {
        $tenant->load(['user', 'leases.unit.property', 'maintenanceRequests.unit']);
        return view('landlord.tenants.show', compact('tenant'));
    }

    public function edit(string $org, Tenant $tenant)
    {
        $tenant->load('user');
        return view('landlord.tenants.edit', compact('tenant'));
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
        ];

        if ($request->hasFile('id_document')) {
            $data['id_document'] = $request->file('id_document')->store('tenant-documents', 'public');
        }

        $tenant->update($data);

        return redirect()->route('landlord.tenants.show', $tenant)->with('success', 'Tenant updated.');
    }

    public function destroy(string $org, Tenant $tenant)
    {
        $tenant->user->delete();
        $tenant->delete();
        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant deleted.');
    }
}
