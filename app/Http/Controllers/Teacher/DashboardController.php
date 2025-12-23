<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $teacherMajorId = $teacher?->major_id;

        if (!$teacherMajorId) {
            // aman: tetap tampil dashboard tapi semua 0 + info di view
            $waitingTeacher = 0;
            $approvedByTeacher = 0;
            $activeInterns = 0;
            $logbookLast7Days = 0;

            return view('teacher.dashboard', compact(
                'waitingTeacher', 'approvedByTeacher', 'activeInterns', 'logbookLast7Days', 'teacherMajorId'
            ));
        }

        $waitingTeacher = InternshipApplication::where('status', InternshipApplication::STATUS_WAITING_TEACHER)
            ->whereHas('student', fn($q) => $q->where('major_id', $teacherMajorId))
            ->count();

        $approvedByTeacher = InternshipApplication::where('status', InternshipApplication::STATUS_APPROVED_BY_TEACHER)
            ->whereHas('student', fn($q) => $q->where('major_id', $teacherMajorId))
            ->count();

        $activeInterns = InternshipApplication::where('status', InternshipApplication::STATUS_ACCEPTED)
            ->whereHas('student', fn($q) => $q->where('major_id', $teacherMajorId))
            ->count();

        $logbookLast7Days = LogbookEntry::whereIn(
                'internship_application_id',
                InternshipApplication::select('id')
                    ->whereHas('student', fn($q) => $q->where('major_id', $teacherMajorId))
            )
            ->whereDate('log_date', '>=', now()->subDays(7)->toDateString())
            ->count();

        return view('teacher.dashboard', compact(
            'waitingTeacher',
            'approvedByTeacher',
            'activeInterns',
            'logbookLast7Days',
            'teacherMajorId'
        ));
    }
}
