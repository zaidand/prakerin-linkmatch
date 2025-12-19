<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
