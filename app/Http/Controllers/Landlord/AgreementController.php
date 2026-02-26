<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\AgreementStatus;
use App\Models\AgentAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $agreements = AgentAgreement::where('landlord_id', $request->user()->landlord->id)
            ->with('agent')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('landlord.agreements.index', compact('agreements'));
    }

    public function show(Request $request, AgentAgreement $agreement)
    {
        if ($agreement->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this agreement.');
        }

        $agreement->load('agent');

        return view('landlord.agreements.show', compact('agreement'));
    }

    public function sign(Request $request, AgentAgreement $agreement)
    {
        if ($agreement->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this agreement.');
        }

        // Can only sign pending agreements
        if ($agreement->status !== AgreementStatus::PENDING) {
            return back()->with('error', 'This agreement cannot be signed.');
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
            $fileName = 'signatures/' . $agreement->id . '_' . time() . '.png';
            Storage::disk('public')->put($fileName, base64_decode($image));
        }

        $agreement->update([
            'signature_url' => $fileName,
            'signed_at' => now(),
            'status' => AgreementStatus::ACTIVE,
        ]);

        return redirect()->route('landlord.agreements.show', $agreement)
            ->with('success', 'Agreement signed successfully! The agreement is now active.');
    }
}
