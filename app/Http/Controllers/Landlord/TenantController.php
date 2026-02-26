<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $landlordId = $request->user()->landlord->id;

        // Get all tenants with leases on landlord's properties
        $leases = Lease::whereHas('unit.property', function ($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })
        ->with(['tenant.user', 'unit.property'])
        ->latest()
        ->paginate(15);

        return view('landlord.tenants.index', compact('leases'));
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
            'password' => 'required|string|min:8',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => UserRole::TENANT,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
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

        Tenant::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'emergency_contact' => $emergencyContact,
            'id_document' => $idDocPath,
        ]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant created.');
    }

    public function show(Request $request, Tenant $tenant)
    {
        $tenant->load(['user', 'leases.unit.property', 'maintenanceRequests.unit']);
        return view('landlord.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('user');
        return view('landlord.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
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

    public function destroy(Tenant $tenant)
    {
        $tenant->user->delete();
        $tenant->delete();
        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant deleted.');
    }
}
