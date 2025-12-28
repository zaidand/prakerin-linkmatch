<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Contracts\Auth\Access\Gate;

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

        // rule jurusan
        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }

        // jalankan authorize hanya jika Gate tersedia (agar tidak error Gate is not instantiable)
        if (app()->bound(Gate::class)) {
            $this->authorize('view', $application);
        }

        return view('teacher.applications.show', compact('application', 'teacher'));
    }

    public function approve(Request $request, InternshipApplication $application)
    {
    $teacher = Auth::user()->teacher;

    // Validasi guru hanya boleh lihat/approve siswa jurusan yang sama (rule kamu)
    if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
        abort(403);
    }

    // middleware sudah role:teacher, tapi kalau mau tetap cek aman:
    if (Auth::user()?->role?->name !== 'teacher') {
        abort(403);
    }

    $validated = $request->validate([
        'teacher_note' => 'nullable|string|max:500',
    ]);

    // ✅ Update status & catatan (tanpa save())
    $application->update([
        'status' => InternshipApplication::STATUS_APPROVED_BY_TEACHER,
        'teacher_note' => $validated['teacher_note'] ?? null,
        'teacher_verified_at' => now(),
    ]);

    // kirim notifikasi ke siswa
    $studentUser = $application->student?->user;

    if ($studentUser) {
        $studentUser->notify(new ApplicationStatusUpdated($application));
    }

    return redirect()
        ->route('teacher.applications.index')
        ->with('success', 'Pengajuan telah disetujui dan direkomendasikan ke admin.');
    }

    public function destroy(InternshipApplication $application)
    {
        // hanya boleh delete saat masih menunggu verifikasi guru
        if ($application->status !== InternshipApplication::STATUS_WAITING_TEACHER) {
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
        // kalau Gate/Policy belum kamu aktifkan, jangan pakai authorize di sini juga
        if (app()->bound(Gate::class)) {
            $this->authorize('verify', $application);
        }

        $validated = $request->validate([
            // karena kamu sudah tidak pakai revision, sebaiknya update hanya untuk approved_by_teacher saja
            'status' => 'required|in: approved_by_teacher', InternshipApplication::STATUS_APPROVED_BY_TEACHER,
            'teacher_note' => 'nullable|string|max:500',
        ]);

        $application->update([
            'status' => $validated['status'],
            'teacher_note' => $validated['teacher_note'] ?? null,
            'teacher_verified_at' => now(),
        ]);

        return redirect()
            ->route('teacher.applications.index')
            ->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
