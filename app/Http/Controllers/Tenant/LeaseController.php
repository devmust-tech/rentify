<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Enums\LeaseStatus;
use App\Enums\UnitStatus;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $leases = $tenant ? $tenant->leases()->with(['unit.property'])->get() : collect();
        $activeLease = $leases->firstWhere('status', LeaseStatus::ACTIVE);
        $pendingLeases = $leases->where('status', LeaseStatus::PENDING);

        return view('tenant.lease.index', compact('leases', 'activeLease', 'pendingLeases'));
    }

    public function sign(Request $request, Lease $lease)
    {
        $tenant = $request->user()->tenant;

        // Auth check - lease must belong to this tenant
        if ($lease->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Can only sign pending leases
        if ($lease->status !== LeaseStatus::PENDING) {
            return back()->with('error', 'This lease cannot be signed.');
        }

        $request->validate([
            'signature' => 'required|string', // base64 signature data
            'agree_terms' => 'required|accepted',
        ]);

        // Save signature as image file
        $signatureData = $request->input('signature');
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $fileName = 'signatures/' . $lease->id . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, base64_decode($image));

        $lease->update([
            'signature_url' => $fileName,
            'signed_at' => now(),
            'status' => LeaseStatus::ACTIVE,
        ]);

        // Mark unit as occupied
        $lease->unit->update(['status' => UnitStatus::OCCUPIED]);

        return redirect()->route('tenant.lease.index')
            ->with('success', 'Lease signed successfully! Your tenancy is now active.');
    }
}
