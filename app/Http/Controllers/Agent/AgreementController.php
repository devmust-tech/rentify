<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\AgreementStatus;
use App\Models\AgentAgreement;
use App\Models\Landlord;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $agreements = AgentAgreement::where('agent_id', $request->user()->id)
            ->with('landlord.user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('agent.agreements.index', compact('agreements'));
    }

    public function create()
    {
        $landlords = Landlord::with('user')->get();

        return view('agent.agreements.create', compact('landlords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'landlord_id' => 'required|exists:landlords,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'payment_day' => 'required|integer|min:1|max:31',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'terms' => 'nullable|string',
        ]);

        AgentAgreement::create([
            ...$validated,
            'agent_id' => $request->user()->id,
            'status' => AgreementStatus::PENDING,
        ]);

        return redirect()->route('agent.agreements.index')
            ->with('success', 'Agreement created successfully. Awaiting landlord signature.');
    }

    public function show(Request $request, AgentAgreement $agreement)
    {
        if ($agreement->agent_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this agreement.');
        }

        $agreement->load('landlord.user');

        return view('agent.agreements.show', compact('agreement'));
    }
}
