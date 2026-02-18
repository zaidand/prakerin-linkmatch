<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $notifications = DatabaseNotification::query()
            ->where('notifiable_type', $user::class)
            ->where('notifiable_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $notification = DatabaseNotification::query()
            ->where('id', $id)
            ->where('notifiable_type', $user::class)
            ->where('notifiable_id', $user->id)
            ->firstOrFail();
        $notification->markAsRead();

        return back();
    }

     public function markAllRead(Request $request)
    {
        $user = $request->user();
        if (! $user instanceof User) {
            abort(403);
        }

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
