<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Industry;
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

            // ⬇️ TAMBAHAN: hitung jumlah aplikasi yang mengisi kuota
            ->withCount([
                'applications as active_applications_count' => function ($q) {
                    // SESUAIKAN kalau kamu punya status lain
                    // yang juga harus mengurangi kuota
                    $q->where('status', 'accepted');
                },
            ])
            // ⬆️ TAMBAHAN SELESAI

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

        // hitung sisa kuota per industri
        $industries->getCollection()->transform(function ($industry) {
            // total kuota dari semua kuota aktif (periode yang sedang tampil)
            $totalQuota = $industry->quotas->sum('max_students');

            // jumlah siswa yang sudah mengisi kuota (dari withCount di atas)
            $used = $industry->active_applications_count ?? 0;

            // tidak boleh negatif
            $industry->remaining_slots = max(0, $totalQuota - $used);

            return $industry;
        });

        return view('student.industries.index', compact('industries', 'student'));
    }
}
