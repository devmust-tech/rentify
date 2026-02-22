<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        // Get all active leases from units in landlord's properties
        $leases = Lease::whereHas('unit.property', function ($query) use ($request) {
            $query->where('landlord_id', $request->user()->landlord->id);
        })
        ->where('status', 'active')
        ->with(['tenant.user', 'unit.property'])
        ->paginate(15);

        return view('landlord.tenants.index', compact('leases'));
    }
}
