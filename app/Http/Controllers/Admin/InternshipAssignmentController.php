<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\IndustryQuota;
use Illuminate\Http\Request;

class InternshipAssignmentController extends Controller
{
    public function index()
    {
        $applications = InternshipApplication::with(['student.user', 'student.major', 'industry', 'quota'])
            ->where('status', InternshipApplication::STATUS_APPROVED_BY_TEACHER)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    public function assignForm(InternshipApplication $application)
    {
        $industry = $application->industry;
        $quotas = $industry->quotas()
            ->orderByDesc('start_date')
            ->get();

        return view('admin.applications.assign', compact('application', 'industry', 'quotas'));
    }

    public function assign(Request $request, InternshipApplication $application)
    {
        $this->authorize('assign', $application);
        $request->validate([
            'industry_quota_id' => 'required|exists:industry_quotas,id',
            'admin_note'        => 'nullable|string',
        ]);

        $quota = IndustryQuota::findOrFail($request->industry_quota_id);

        // pastikan kuota milik industri yang sama
        if ($quota->industry_id !== $application->industry_id) {
            abort(403);
        }

        // cek kapasitas kuota (sederhana: hitung semua yg sudah dialokasikan)
        $usedCount = $quota->applications()
            ->whereIn('status', [
                InternshipApplication::STATUS_APPROVED_BY_TEACHER,
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
                InternshipApplication::STATUS_ACCEPTED,
            ])
            ->count();

        if ($usedCount >= $quota->max_students) {
            return back()->withErrors('Kuota industri ini sudah penuh.');
        }

        $application->update([
            'industry_quota_id'  => $quota->id,
            'status'             => InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
            'admin_note'         => $request->admin_note,
            'admin_assigned_at'  => now(),
        ]);

        return redirect()->route('admin.applications.letter', [
        'application' => $application->id,
        'print' => 1,
        ])->with('success', 'Penempatan ditetapkan. Surat pengantar siap dicetak.');
    }
}
