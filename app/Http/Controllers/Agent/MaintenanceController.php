<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Mail\MaintenanceUpdated;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        $maintenanceRequests = MaintenanceRequest::whereIn('unit_id', $unitIds)
            ->with(['unit.property', 'tenant.user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('agent.maintenance.index', compact('maintenanceRequests'));
    }

    public function show(MaintenanceRequest $maintenance)
    {
        $maintenance->load(['unit.property', 'tenant.user']);
        return view('agent.maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRequest $maintenance)
    {
        $maintenance->load(['unit.property', 'tenant.user']);
        return view('agent.maintenance.edit', compact('maintenance'));
    }

    public function update(Request $request, MaintenanceRequest $maintenance)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'priority' => 'sometimes|string',
            'assigned_to' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && !$maintenance->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $maintenance->update($validated);

        $maintenance->load('tenant.user', 'unit.property');
        Mail::to($maintenance->tenant->user->email)->queue(new MaintenanceUpdated($maintenance));

        return redirect()->route('agent.maintenance.show', $maintenance)->with('success', 'Request updated.');
    }
}
