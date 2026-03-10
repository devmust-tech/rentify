<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Mail\UserInvitation;
use App\Models\Landlord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class LandlordController extends Controller
{
    public function index()
    {
        $landlords = Landlord::with(['user', 'properties'])->paginate(15);
        return view('agent.landlords.index', compact('landlords'));
    }

    public function create()
    {
        return view('agent.landlords.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'national_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'mpesa_number' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt(Str::random(32)),
            'role' => UserRole::LANDLORD,
            'status' => UserStatus::INACTIVE,
        ]);

        $landlord = Landlord::create([
            'user_id' => $user->id,
            'national_id' => $request->national_id,
            'payment_details' => [
                'bank_name' => $request->bank_name,
                'bank_account' => $request->bank_account,
                'mpesa_number' => $request->mpesa_number,
            ],
        ]);

        $this->sendInvitationEmail($landlord);

        return redirect()->route('agent.landlords.index')
            ->with('success', 'Landlord created. An invitation email has been sent to ' . $user->email . '.');
    }

    public function show(string $org, Landlord $landlord)
    {
        $landlord->load(['user', 'properties.units']);
        return view('agent.landlords.show', compact('landlord'));
    }

    public function edit(string $org, Landlord $landlord)
    {
        $landlord->load('user');
        return view('agent.landlords.edit', compact('landlord'));
    }

    public function update(Request $request, string $org, Landlord $landlord)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $landlord->user_id,
            'phone' => 'required|string',
            'national_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'mpesa_number' => 'nullable|string',
        ]);

        $landlord->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $landlord->update([
            'national_id' => $request->national_id,
            'payment_details' => [
                'bank_name' => $request->bank_name,
                'bank_account' => $request->bank_account,
                'mpesa_number' => $request->mpesa_number,
            ],
        ]);

        return redirect()->route('agent.landlords.show', $landlord)->with('success', 'Landlord updated.');
    }

    public function destroy(string $org, Landlord $landlord)
    {
        $landlord->user->delete();
        $landlord->delete();
        return redirect()->route('agent.landlords.index')->with('success', 'Landlord deleted.');
    }

    public function sendInvite(Request $request, string $org, Landlord $landlord)
    {
        $this->sendInvitationEmail($landlord);
        return back()->with('success', "Invitation sent to {$landlord->user->email}.");
    }

    private function sendInvitationEmail(Landlord $landlord): void
    {
        $org = app('currentOrganization');

        $inviteUrl = URL::temporarySignedRoute(
            'landlord.invitation.show',
            now()->addHours(72),
            ['landlord' => $landlord->id]
        );

        Mail::to($landlord->user->email)->queue(
            new UserInvitation($landlord->user, $inviteUrl, $org->name, 'landlord')
        );
    }
}
