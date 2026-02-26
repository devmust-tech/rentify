<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\NotificationType;
use App\Mail\MaintenanceUpdated;
use App\Models\MaintenanceNote;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MaintenanceController extends Controller
{
    private function getLandlordUnitIds(Request $request)
    {
        $propertyIds = Property::where('landlord_id', $request->user()->landlord->id)->pluck('id');
        return Unit::whereIn('property_id', $propertyIds)->pluck('id');
    }

    private function authorizeLandlordMaintenance(Request $request, MaintenanceRequest $maintenance): void
    {
        $unitIds = $this->getLandlordUnitIds($request);
        if (!$unitIds->contains($maintenance->unit_id)) {
            abort(403, 'Unauthorized access to this maintenance request.');
        }
    }

    public function index(Request $request)
    {
        $unitIds = $this->getLandlordUnitIds($request);

        $maintenanceRequests = MaintenanceRequest::whereIn('unit_id', $unitIds)
            ->with(['unit.property', 'tenant.user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('landlord.maintenance.index', compact('maintenanceRequests'));
    }

    public function show(Request $request, MaintenanceRequest $maintenance)
    {
        $this->authorizeLandlordMaintenance($request, $maintenance);
        $maintenance->load(['unit.property', 'tenant.user', 'notes.user']);
        return view('landlord.maintenance.show', compact('maintenance'));
    }

    public function edit(Request $request, MaintenanceRequest $maintenance)
    {
        $this->authorizeLandlordMaintenance($request, $maintenance);
        $maintenance->load(['unit.property', 'tenant.user']);
        return view('landlord.maintenance.edit', compact('maintenance'));
    }

    public function update(Request $request, MaintenanceRequest $maintenance)
    {
        $this->authorizeLandlordMaintenance($request, $maintenance);

        $validated = $request->validate([
            'status' => 'required|string',
            'priority' => 'sometimes|string',
            'assigned_to' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && !$maintenance->resolved_at) {
            $validated['resolved_at'] = now();
        }

        // If a note was provided, create a MaintenanceNote record
        $noteText = $validated['resolution_notes'] ?? null;
        if ($noteText) {
            MaintenanceNote::create([
                'maintenance_request_id' => $maintenance->id,
                'user_id' => $request->user()->id,
                'note' => $noteText,
            ]);
        }

        $maintenance->update($validated);

        $maintenance->load('tenant.user', 'unit.property');
        Mail::to($maintenance->tenant->user->email)->queue(new MaintenanceUpdated($maintenance));

        app(NotificationService::class)->notify(
            user: $maintenance->tenant->user,
            type: NotificationType::MAINTENANCE_UPDATE,
            subject: 'Maintenance Request Updated',
            message: 'Your maintenance request "' . $maintenance->title . '" has been updated. Status: ' . $maintenance->status->value . '.',
            sendEmail: false,
        );

        return redirect()->route('landlord.maintenance.show', $maintenance)->with('success', 'Request updated.');
    }

    public function addNote(Request $request, MaintenanceRequest $maintenance)
    {
        $this->authorizeLandlordMaintenance($request, $maintenance);

        $validated = $request->validate([
            'note' => 'required|string|max:5000',
        ]);

        MaintenanceNote::create([
            'maintenance_request_id' => $maintenance->id,
            'user_id' => $request->user()->id,
            'note' => $validated['note'],
        ]);

        return redirect()->route('landlord.maintenance.show', $maintenance)->with('success', 'Note added successfully.');
    }
}
