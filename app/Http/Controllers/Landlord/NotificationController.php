<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('landlord.notifications.index', compact('notifications'));
    }

    public function show(Request $request, string $org, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->markAsRead();

        return view('landlord.notifications.show', compact('notification'));
    }

    public function poll(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'unreadCount' => $request->user()->notifications()->unread()->count(),
            'itemsHtml' => view('landlord.notifications.partials.list', compact('notifications'))->render(),
        ]);
    }
}
