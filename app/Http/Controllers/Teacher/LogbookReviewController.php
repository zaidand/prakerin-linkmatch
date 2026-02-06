<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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

    /**
     * Menyimpan komentar guru pada entri logbook siswa.
     */
    public function comment(Request $request, LogbookEntry $logbookEntry)
    {
    $teacher = Auth::user()->teacher;

    if (! $teacher) {
        abort(403, 'Data guru pembimbing tidak ditemukan.');
    }

    $application = $logbookEntry->internshipApplication;
    if (! $application || ! $application->student) {
        abort(404);
    }

    // Guard sederhana: guru hanya boleh akses siswa dalam jurusan yang sama (sesuai pola MonitoringController)
    if ($teacher->major_id && $application->student->major_id !== $teacher->major_id) {
        abort(403);
    }

    $data = $request->validate([
        'teacher_comment' => 'nullable|string|max:2000',
    ]);

    $logbookEntry->update([
        'teacher_comment' => $data['teacher_comment'] ?? null,
    ]);

    return back()->with('success', 'Komentar guru berhasil disimpan.');
    }

}
