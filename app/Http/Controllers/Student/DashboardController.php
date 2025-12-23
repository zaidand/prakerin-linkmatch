<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user?->student;

        if (! $student) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $studentId = $student->id;
        $today = now()->toDateString();

        $latestApplication = InternshipApplication::where('student_id', $studentId)
            ->latest()
            ->first();

        $logbookToday = LogbookEntry::whereIn(
                'internship_application_id',
                InternshipApplication::select('id')->where('student_id', $studentId)
            )
            ->whereDate('log_date', $today)
            ->count();

        $myPendingLogbooks = LogbookEntry::whereIn(
                'internship_application_id',
                InternshipApplication::select('id')->where('student_id', $studentId)
            )
            ->where('status', 'pending')
            ->count();

        // ✅ Cara aman hitung notifikasi belum dibaca (tanpa unreadNotifications())
        $unreadNotif = DatabaseNotification::query()
            ->where('notifiable_type', $user::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('student.dashboard', compact(
            'student',
            'latestApplication',
            'logbookToday',
            'myPendingLogbooks',
            'unreadNotif'
        ));
    }
}
