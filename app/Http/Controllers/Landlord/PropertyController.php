<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = $request->user()->landlord->properties()
            ->with(['units'])
            ->paginate(12);

        return view('landlord.properties.index', compact('properties'));
    }

    public function show(Request $request, Property $property)
    {
        // Ensure the property belongs to this landlord
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->load(['units.activeLease.tenant.user']);

        return view('landlord.properties.show', compact('property'));
    }
}
