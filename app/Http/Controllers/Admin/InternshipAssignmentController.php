<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\IndustryQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternshipAssignmentController extends Controller
{
    public function index()
    {
        $applications = InternshipApplication::with(['student.user', 'student.major', 'industry', 'quota', 'requestedQuota'])
            ->where('status', InternshipApplication::STATUS_APPROVED_BY_TEACHER)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    public function assignForm(InternshipApplication $application)
    {
        $application->load(['requestedQuota']);
        $industry = $application->industry;
        $quotas = $industry->quotas()
            ->orderByDesc('start_date')
            ->get();

        // Strict reservation: kuota dianggap terpakai sejak siswa memilih (requested_quota_id),
        // dan setelah admin assign pindah ke industry_quota_id (tanpa double count).
        $activeStatuses = [
            InternshipApplication::STATUS_WAITING_TEACHER,
            InternshipApplication::STATUS_APPROVED_BY_TEACHER,
            InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
            InternshipApplication::STATUS_ACCEPTED,
        ];

        $quotaIds = $quotas->pluck('id')->values();
        $usageByQuota = collect();

        if ($quotaIds->isNotEmpty()) {
            $usageByQuota = InternshipApplication::query()
                ->selectRaw('COALESCE(industry_quota_id, requested_quota_id) as quota_id, COUNT(*) as cnt')
                ->whereIn('status', $activeStatuses)
                ->where(function ($q) use ($quotaIds) {
                    $q->whereIn('industry_quota_id', $quotaIds)
                      ->orWhere(function ($q2) use ($quotaIds) {
                          $q2->whereNull('industry_quota_id')
                             ->whereIn('requested_quota_id', $quotaIds);
                      });
                })
                ->groupBy('quota_id')
                ->get()
                ->pluck('cnt', 'quota_id');
        }

        $currentEffectiveQuotaId = $application->industry_quota_id ?: $application->requested_quota_id;

        $quotas->transform(function ($quota) use ($usageByQuota, $currentEffectiveQuotaId) {
            $used = (int) ($usageByQuota[$quota->id] ?? 0);
            $remaining = max(0, (int)$quota->max_students - $used);

            // can_select: kuota boleh dipilih jika masih ada sisa,
            // atau jika kuota ini adalah kuota yang sedang dipegang aplikasi ini (tidak menambah konsumsi slot).
            $quota->used_slots = $used;
            $quota->remaining_slots = $remaining;
            $quota->can_select = ($remaining > 0) || ($currentEffectiveQuotaId === $quota->id);

            return $quota;
        });

        return view('admin.applications.assign', compact('application', 'industry', 'quotas'));
    }

    public function assign(Request $request, InternshipApplication $application)
    {
        $this->authorize('assign', $application);
        $data = $request->validate([
            'industry_quota_id' => 'required|exists:industry_quotas,id',
            'admin_note'        => 'nullable|string',
        ]);

        $activeStatuses = [
            InternshipApplication::STATUS_WAITING_TEACHER,
            InternshipApplication::STATUS_APPROVED_BY_TEACHER,
            InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
            InternshipApplication::STATUS_ACCEPTED,
        ];

        return DB::transaction(function () use ($data, $application, $request, $activeStatuses) {
            // Lock kuota target supaya tidak terjadi race condition antar admin.
            $quota = IndustryQuota::whereKey($data['industry_quota_id'])->lockForUpdate()->firstOrFail();

        // pastikan kuota milik industri yang sama
            if ($quota->industry_id !== $application->industry_id) {
                abort(403);
            }

            // Kapasitas strict: kuota dianggap terpakai sejak siswa memilih kuota (requested_quota_id),
            // dan setelah admin assign kuota efektif pindah ke industry_quota_id (tanpa double count).
            $usedCount = InternshipApplication::query()
                ->whereIn('status', $activeStatuses)
                ->where('id', '!=', $application->id)
                ->where(function ($q) use ($quota) {
                    $q->where('industry_quota_id', $quota->id)
                      ->orWhere(function ($q2) use ($quota) {
                          $q2->whereNull('industry_quota_id')
                             ->where('requested_quota_id', $quota->id);
                      });
                })
                ->count();

                if ($usedCount >= $quota->max_students) {
                return back()->withErrors([
                    'industry_quota_id' => 'Kuota industri ini sudah penuh.',
                ]);
            }

            $application->update([
                'industry_quota_id'  => $quota->id,
                'status'             => InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
                'admin_note'         => $data['admin_note'] ?? null,
                'admin_assigned_at'  => now(),
            ]);

            return redirect()->route('admin.applications.letter', [
                'application' => $application->id,
                'print'       => 1,
            ])->with('success', 'Penempatan ditetapkan. Surat pengantar siap dicetak.');
        });
    }
}
