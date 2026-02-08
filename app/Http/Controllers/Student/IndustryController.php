<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\InternshipApplication;
use Illuminate\Support\Facades\Auth;

class IndustryController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (! $student) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $today = now()->toDateString();

        $industries = Industry::with([
                'majors',
                'quotas' => function ($q) use ($today) {
                    $q->where('is_active', true)
                      ->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today);
                },
            ])

            ->where('status', 'active')
            // Link & Match jurusan siswa
            ->whereHas('majors', function ($q) use ($student) {
                $q->where('majors.id', $student->major_id);
            })
            // hanya yang punya kuota aktif
            ->whereHas('quotas', function ($q) use ($today) {
                $q->where('is_active', true)
                  ->whereDate('start_date', '<=', $today)
                  ->whereDate('end_date', '>=', $today);
            })
            ->paginate(10);

            // Hitung pemakaian kuota secara strict per-kuota (reservasi sejak siswa mengajukan).
        $quotaIds = $industries->getCollection()
            ->pluck('quotas')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values();

        $usageByQuota = collect();

        if ($quotaIds->isNotEmpty()) {
            $activeStatuses = [
                InternshipApplication::STATUS_WAITING_TEACHER,
                InternshipApplication::STATUS_APPROVED_BY_TEACHER,
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
                InternshipApplication::STATUS_ACCEPTED,
            ];

            // quota_id efektif = COALESCE(industry_quota_id, requested_quota_id)
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

        // set remaining_slots per kuota + total per industri
        $industries->getCollection()->transform(function ($industry) use ($usageByQuota) {
            $industry->quotas->transform(function ($quota) use ($usageByQuota) {
                $used = (int) ($usageByQuota[$quota->id] ?? 0);

                $quota->used_slots = $used;
                $quota->remaining_slots = max(0, $quota->max_students - $used);

                return $quota;
            });

            $industry->remaining_slots = (int) $industry->quotas->sum('remaining_slots');
            $firstAvailable = $industry->quotas->first(function ($q) {
                return ($q->remaining_slots ?? 0) > 0;
            });

            $industry->first_available_quota_id = $firstAvailable?->id;

            return $industry;
        });

        return view('student.industries.index', compact('industries', 'student'));
    }
}
