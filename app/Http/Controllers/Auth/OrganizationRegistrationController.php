<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OrganizationStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class OrganizationRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.org-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'org_name' => ['required', 'string', 'max:100'],
            'slug'     => ['required', 'string', 'max:63'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'role'     => ['nullable', 'string', 'in:agent,landlord'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $org = DB::transaction(function () use ($request) {
            // Create the organization (pending approval)
            $org = Organization::create([
                'name'         => $request->org_name,
                'slug'         => $request->slug,
                'status'       => OrganizationStatus::PENDING,
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Bind org so creating the user auto-fills organization_id
            app()->instance('currentOrganization', $org);

            // Create the owner user
            $roleEnum = $request->role === 'landlord' ? UserRole::LANDLORD : UserRole::AGENT;
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'role'              => $roleEnum,
                'status'            => UserStatus::INACTIVE, // Inactive until admin approves
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Set this user as the organization owner
            $org->update(['owner_id' => $user->id]);

            return $org;
        });

        // Local dev: auto-add hosts entry so the subdomain resolves
        if (app()->environment('local')) {
            $hostsEntry = "127.0.0.1 {$org->slug}." . config('app.domain');
            $hostsFile  = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
            $existing   = @file_get_contents($hostsFile) ?: '';
            if (!str_contains($existing, $hostsEntry)) {
                @file_put_contents($hostsFile, PHP_EOL . $hostsEntry, FILE_APPEND);
            }
        }

        return redirect()->to($org->subdomainUrl() . '/login')
            ->with('status', 'Your workspace has been created and is pending approval. You will be notified once it is active.');
    }
}
