<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Enums\LeaseStatus;
use App\Enums\NegotiationStatus;
use App\Enums\NotificationType;
use App\Enums\UnitStatus;
use App\Models\Lease;
use App\Models\RentNegotiation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $leases = $tenant ? $tenant->leases()->with(['unit.property', 'negotiations.proposer'])->get() : collect();
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
            'signature' => 'nullable|string',
            'signature_photo' => 'nullable|image|max:5120',
            'agree_terms' => 'required|accepted',
        ]);

        if (!$request->input('signature') && !$request->hasFile('signature_photo')) {
            return back()->withErrors(['signature' => 'Please draw or upload a signature.']);
        }

        // Save signature - either from canvas (base64) or file upload
        if ($request->hasFile('signature_photo')) {
            $fileName = $request->file('signature_photo')->store('signatures', 'public');
        } else {
            $signatureData = $request->input('signature');
            $image = str_replace('data:image/png;base64,', '', $signatureData);
            $image = str_replace(' ', '+', $image);
            $fileName = 'signatures/' . $lease->id . '_' . time() . '.png';
            Storage::disk('public')->put($fileName, base64_decode($image));
        }

        $lease->update([
            'signature_url' => $fileName,
            'signed_at' => now(),
            'status' => LeaseStatus::ACTIVE,
        ]);

        // Mark unit as occupied
        $lease->unit->update(['status' => UnitStatus::OCCUPIED]);

        // Notify the property's agent that the tenant signed the lease
        $lease->load('unit.property.agent', 'tenant.user');
        $agent = $lease->unit->property->agent;
        if ($agent) {
            app(NotificationService::class)->notify(
                user: $agent,
                type: NotificationType::GENERAL,
                subject: 'Lease Signed',
                message: $lease->tenant->user->name . ' has signed the lease for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . '.',
                sendEmail: true,
            );
        }

        return redirect()->route('tenant.lease.index')
            ->with('success', 'Lease signed successfully! Your tenancy is now active.');
    }

    public function negotiate(Request $request, Lease $lease)
    {
        $tenant = $request->user()->tenant;

        // Auth check - lease must belong to this tenant
        if ($lease->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Can only negotiate on pending leases
        if ($lease->status !== LeaseStatus::PENDING) {
            return back()->with('error', 'Rent negotiation is only available for pending leases.');
        }

        $request->validate([
            'proposed_rent' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        // Check if there's already a pending negotiation
        $hasPendingNegotiation = $lease->negotiations()
            ->where('status', NegotiationStatus::PENDING)
            ->exists();

        if ($hasPendingNegotiation) {
            return back()->with('error', 'There is already a pending negotiation. Please wait for a response.');
        }

        RentNegotiation::create([
            'lease_id' => $lease->id,
            'proposed_by' => $request->user()->id,
            'proposed_rent' => $request->input('proposed_rent'),
            'message' => $request->input('message'),
            'status' => NegotiationStatus::PENDING,
        ]);

        // Notify the agent
        $lease->load('unit.property.agent', 'tenant.user');
        $agent = $lease->unit->property->agent;
        if ($agent) {
            app(NotificationService::class)->notify(
                user: $agent,
                type: NotificationType::GENERAL,
                subject: 'Rent Negotiation Proposal',
                message: $lease->tenant->user->name . ' has proposed a rent of KSh ' . number_format($request->input('proposed_rent'), 2) . ' for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . '.',
                sendEmail: true,
            );
        }

        return back()->with('success', 'Your rent proposal has been submitted. You will be notified once the agent responds.');
    }
}
