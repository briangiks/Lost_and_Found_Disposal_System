<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** Full notification center — every notification, newest first. */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);

        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markRead(Request $request, string $notification)
    {
        $record = $request->user()->notifications()->where('id', $notification)->firstOrFail();
        $record->markAsRead();

        if ($url = data_get($record->data, 'url')) {
            return redirect($url);
        }

        return back();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('status', 'All notifications marked as read.');
    }
}
