<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Landlord;
use App\Models\Notification;
use App\Models\Tenant;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('agent.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        $notification->markAsRead();
        return view('agent.notifications.show', compact('notification'));
    }

    public function create()
    {
        $landlords = Landlord::with('user')->get();
        $tenants = Tenant::with('user')->get();
        return view('agent.notifications.create', compact('landlords', 'tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
        ]);

        Notification::create([
            ...$validated,
            'sent_at' => now(),
        ]);

        return redirect()->route('agent.notifications.index')->with('success', 'Notification sent.');
    }
}
