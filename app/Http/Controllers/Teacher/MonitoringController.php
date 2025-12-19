<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\MonitoringNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    protected function getTeacher()
    {
        return Auth::user()->teacher;
    }

    public function index()
    {
        $teacher = $this->getTeacher();

        // Sederhana: guru memonitor siswa dengan jurusan yang sama
        $applications = InternshipApplication::with(['student.user', 'student.major', 'industry'])
            ->where('status', InternshipApplication::STATUS_ACCEPTED)
            ->whereHas('student', function ($q) use ($teacher) {
                if ($teacher && $teacher->major_id) {
                    $q->where('major_id', $teacher->major_id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.monitoring.index', compact('applications', 'teacher'));
    }

    public function show(InternshipApplication $application)
    {
    $teacher = Auth::user()->teacher;

    if (! $teacher) {
        abort(403, 'Data guru pembimbing tidak ditemukan.');
    }

    // if ($application->student->teacher_id !== $teacher->id) {
    //     abort(403, 'Anda tidak berhak melihat siswa ini.');
    // }

    $application->load([
        'student.user',
        'student.major',
        'industry',
    ]);

    $logbookEntries = $application->logbookEntries()
        ->orderByDesc('log_date')
        ->get();

    $monitoringNotes = $application->monitoringNotes()
        ->orderByDesc('note_date')
        ->get();

    return view('teacher.monitoring.show', compact(
        'application',
        'logbookEntries',
        'monitoringNotes'
    ));
    }

    public function storeNote(Request $request, InternshipApplication $application)
    {
        $teacher = $this->getTeacher();

        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }

        $request->validate([
            'note_date' => 'required|date',
            'note'      => 'required|string',
        ]);

        MonitoringNote::create([
            'internship_application_id' => $application->id,
            'teacher_id'                => $teacher->id,
            'note_date'                 => $request->note_date,
            'note'                      => $request->note,
        ]);

        return redirect()->route('teacher.monitoring.show', $application)
            ->with('success', 'Catatan monitoring berhasil disimpan.');
    }
}
