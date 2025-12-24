<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications
            ->sortByDesc('created_at')
            ->take(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->notifications->where('id', $id)->first();
        if (!$notification) {
            abort(404);
        }
        $notification->markAsRead();

        return back();
    }

     public function markAllRead(Request $request)
    {
        $user = $request->user();

        // Cara aman: update langsung tabel notifications (tanpa unreadNotifications())
        DatabaseNotification::query()
            ->where('notifiable_type', $user::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'ok' => true,
            'unread' => 0,
        ]);
    }
}
