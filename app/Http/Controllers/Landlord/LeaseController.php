<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $leases = Lease::whereHas('unit.property', function ($query) use ($request) {
            $query->where('landlord_id', $request->user()->landlord->id);
        })
        ->with(['tenant.user', 'unit.property'])
        ->latest()
        ->paginate(15);

        return view('landlord.leases.index', compact('leases'));
    }

    public function show(Request $request, Lease $lease)
    {
        // Ensure the lease belongs to a unit in landlord's property
        if ($lease->unit->property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this lease.');
        }

        $lease->load(['tenant.user', 'unit.property', 'invoices.payments']);

        return view('landlord.leases.show', compact('lease'));
    }

    public function approve(Request $request, Lease $lease)
    {
        // Ensure the lease belongs to a unit in landlord's property
        if ($lease->unit->property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this lease.');
        }

        // Require tenant signature before approval
        if (!$lease->signed_at || !$lease->signature_url) {
            return redirect()->route('landlord.leases.show', $lease)
                ->with('error', 'Cannot approve lease — tenant has not signed yet.');
        }

        // Update lease status to active and unit to occupied
        $lease->update([
            'status' => 'active',
        ]);

        $lease->unit->update([
            'status' => 'occupied',
        ]);

        return redirect()->route('landlord.leases.show', $lease)
            ->with('success', 'Lease approved successfully.');
    }
}
