<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\LeaseStatus;
use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show(Request $request)
    {
        $tenant = $request->user()->tenant;

        $lease = $tenant ? Lease::where('tenant_id', $tenant->id)
            ->where('status', LeaseStatus::ACTIVE)
            ->with(['unit.property'])
            ->first() : null;

        if (!$lease) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You do not have an active lease.');
        }

        $unit     = $lease->unit;
        $property = $unit->property;

        return view('tenant.property', compact('lease', 'unit', 'property'));
    }
}
