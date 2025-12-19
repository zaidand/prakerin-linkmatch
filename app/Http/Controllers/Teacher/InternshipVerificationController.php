<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicationStatusUpdated;

class InternshipVerificationController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $applications = InternshipApplication::with(['student.user', 'student.major', 'industry'])
            ->where('status', InternshipApplication::STATUS_WAITING_TEACHER)
            ->whereHas('student', function ($q) use ($teacher) {
                // simple rule: guru lihat siswa dengan jurusan yang sama
                if ($teacher && $teacher->major_id) {
                    $q->where('major_id', $teacher->major_id);
                }
            })
            ->orderBy('created_at')
            ->paginate(10);

        return view('teacher.applications.index', compact('applications', 'teacher'));
    }

    public function show(InternshipApplication $application)
    {
        $teacher = Auth::user()->teacher;

        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }
        $this->authorize('view', $application);

        return view('teacher.applications.show', compact('application', 'teacher'));
    }

    public function approve(Request $request, InternshipApplication $application)
    {
        $teacher = Auth::user()->teacher;

        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }

    if (Auth::user()?->role?->name !== 'teacher') {
        abort(403);
    }

    $validated = $request->validate([
        'teacher_note' => 'nullable|string|max:500',
    ]);

    // ubah status & simpan catatan
    $application->status = 'approved_by_teacher'; // sesuaikan dengan status di DB
    $application->teacher_note = $validated['teacher_note'] ?? null;
    $application->save();

    // kirim notifikasi ke siswa
    $studentUser = $application->student->user ?? null;

    if ($studentUser) {
        $studentUser->notify(new ApplicationStatusUpdated($application));
    }

    return redirect()
        ->route('teacher.applications.index')
        ->with('success', 'Pengajuan telah disetujui dan direkomendasikan ke admin.');
    }

    // public function revision(Request $request, InternshipApplication $application)
    // {
    //     $teacher = Auth::user()->teacher;

    //     if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
    //         abort(403);
    //     }

    // if (Auth::user()?->role?->name !== 'teacher') {
    //     abort(403);
    // }

    // $validated = $request->validate([
    //     'teacher_note' => 'required|string|max:500',
    // ]);

    // $application->status = 'revision'; // sesuaikan dengan value status revisi
    // $application->teacher_note = $validated['teacher_note'];
    // $application->save();

    // $studentUser = $application->student->user ?? null;

    // if ($studentUser) {
    //     $studentUser->notify(new ApplicationStatusUpdated($application));
    // }

    // return redirect()
    //     ->route('teacher.applications.index')
    //     ->with('success', 'Pengajuan dikembalikan ke siswa untuk revisi.');
    // }

    public function destroy(InternshipApplication $application)
    {
        if ($application->status !== 'waiting_teacher_verification') {
            return redirect()
                ->route('teacher.applications.show', $application)
                ->with('error', 'Pengajuan ini tidak bisa dihapus pada status saat ini.');
        }

        $application->delete();

        return redirect()
            ->route('teacher.applications.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function update(Request $request, InternshipApplication $application)
    {
    // Pastikan hanya guru yang boleh verifikasi (sesuai policy)
    $this->authorize('verify', $application);

    $validated = $request->validate([
        'status' => 'required|in:approved_by_teacher,revision', // sesuaikan dengan enum/status di model
        'teacher_note' => 'nullable|string|max:500',
    ]);

    $application->status = $validated['status'];
    $application->teacher_note = $validated['teacher_note'] ?? null;
    $application->save();

    return redirect()
        ->route('teacher.applications.index')
        ->with('success', 'Status pengajuan berhasil diperbarui.');
    }

}
