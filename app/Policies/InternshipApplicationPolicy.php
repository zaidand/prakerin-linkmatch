<?php

namespace App\Policies;

use App\Models\InternshipApplication;
use App\Models\User;

class InternshipApplicationPolicy
{
    /**
     * Siapa saja yang boleh melihat detail 1 pengajuan.
     */
    public function view(User $user, InternshipApplication $application): bool
    {
        $role = $user->role->name ?? null;

        return match ($role) {
            // Siswa: hanya boleh lihat pengajuan miliknya sendiri
            'student' => $application->student
                && $application->student->user_id === $user->id,

            // Guru: boleh lihat semua pengajuan (bisa dibatasi lagi per jurusan kalau mau)
            'teacher' => true,

            // Admin: boleh lihat semua
            'admin' => true,

            // Pembimbing Lapangan: boleh lihat pengajuan yang diarahkan ke industrinya
            'industry_supervisor' => $user->industry
                && $application->industry_id === $user->industry->id,

            default => false,
        };
    }

    /**
     * Guru memverifikasi (UC4) - mengubah status misalnya:
     * pending_teacher -> approved_by_teacher / revision
     */
    public function verify(User $user, InternshipApplication $application): bool
    {
        $role = $user->role->name ?? null;

        return $role === 'teacher';
    }

    /**
     * Admin menetapkan penempatan (UC5) - assign ke industri, ubah status:
     * approved_by_teacher -> assigned
     */
    public function assign(User $user, InternshipApplication $application): bool
    {
        $role = $user->role->name ?? null;

        return $role === 'admin';
    }

    /**
     * Pembimbing Lapangan konfirmasi kesediaan (UC6) - ubah status:
     * assigned -> accepted / rejected
     */
    public function confirm(User $user, InternshipApplication $application): bool
    {
        $role = $user->role->name ?? null;

        if ($role !== 'industry_supervisor') {
            return false;
        }

        // Pastikan pembimbing lapangan hanya boleh confirm aplikasi untuk industrinya
        $industry = $user->industry;

        return $industry && $application->industry_id === $industry->id;
    }
}
