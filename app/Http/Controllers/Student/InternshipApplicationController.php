<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\IndustryQuota;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipApplicationController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $applications = $student->applications()
            ->with(['industry', 'quota'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('student.applications.index', compact('applications', 'student'));
    }

    public function create(Request $request)
    {
        $student = Auth::user()->student;

        $industryId = $request->query('industry_id');
        $quotaId    = $request->query('quota_id');

        $industry = Industry::with('majors')->findOrFail($industryId);

        $quota = null;
        if ($quotaId) {
            $quota = IndustryQuota::where('industry_id', $industry->id)->findOrFail($quotaId);
        }

        // Cek Link & Match
        if (! $industry->majors->contains('id', $student->major_id)) {
            abort(403, 'Industri ini tidak sesuai dengan jurusan Anda.');
        }

        return view('student.applications.create', compact('student', 'industry', 'quota'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        $request->validate([
            'industry_id'       => 'required|exists:industries,id',
            'industry_quota_id' => 'nullable|exists:industry_quotas,id',
            'gpa'               => 'nullable|numeric|min:0|max:100',
            'interest'          => 'required|string',
            'additional_info'   => 'nullable|string',
        ]);

        $industry = Industry::with('majors')->findOrFail($request->industry_id);

        if (! $industry->majors->contains('id', $student->major_id)) {
            abort(403, 'Industri ini tidak sesuai dengan jurusan Anda.');
        }

        $quotaId = $request->industry_quota_id;
        if ($quotaId) {
            IndustryQuota::where('industry_id', $industry->id)->findOrFail($quotaId);
        }

        $application = InternshipApplication::create([
            'student_id'        => $student->id,
            'industry_id'       => $industry->id,
            'industry_quota_id' => $quotaId,
            'status'            => InternshipApplication::STATUS_WAITING_TEACHER,
            'gpa'               => $request->gpa,
            'interest'          => $request->interest,
            'additional_info'   => $request->additional_info,
        ]);

        // Kirim notifikasi ke guru pembimbing & admin
        $teachers = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->get();
        $admins   = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();

        foreach ($teachers->merge($admins) as $user) {
        $user->notify(new \App\Notifications\ApplicationStatusUpdated($application));
        }

        return redirect()->route('student.applications.index')
            ->with('success', 'Pengajuan prakerin berhasil dikirim. Menunggu verifikasi Guru Pembimbing.');
    }

    public function show(InternshipApplication $application)
    {
    // Cek hak akses: siswa hanya boleh melihat pengajuan miliknya
    $this->authorize('view', $application);

    return view('student.applications.show', compact('application'));
    }
}
