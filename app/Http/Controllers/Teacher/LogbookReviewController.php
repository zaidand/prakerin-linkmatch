<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookReviewController extends Controller
{
    /**
     * Detail satu logbook siswa untuk guru pembimbing.
     */
    public function show(LogbookEntry $logbookEntry)
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403, 'Data guru pembimbing tidak ditemukan.');
        }

        $application = $logbookEntry->internshipApplication;

        // Pastikan siswa ini memang bimbingan guru tsb
        // if (! $application || $application->student->teacher_id !== $teacher->id) {
        //     abort(403, 'Anda tidak berhak melihat logbook ini.');
        // }

        $logbookEntry->load([
            'internshipApplication.student.user',
            'internshipApplication.student.major',
            'internshipApplication.industry',
        ]);

        return view('teacher.logbooks.show', compact('logbookEntry', 'application'));
    }

    /**
     * Menampilkan file dokumentasi (foto) logbook untuk guru.
     */
    public function documentation(LogbookEntry $logbookEntry)
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403);
        }

        $application = $logbookEntry->internshipApplication;

        // if (! $application || $application->student->teacher_id !== $teacher->id) {
        //     abort(403);
        // }

        if (! $logbookEntry->evidence_path) {
            abort(404);
        }

        $path = $logbookEntry->evidence_path;

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path($path)
        );
    }
}
