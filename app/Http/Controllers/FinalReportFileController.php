<?php

namespace App\Http\Controllers;

use App\Models\FinalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinalReportFileController extends Controller
{
    public function show(FinalReport $report)
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? null;

        // pastikan relasi yang dibutuhkan sudah dimuat
        $report->load('application.student.user', 'application.industry');

        // cek hak akses berdasarkan role
        switch ($roleName) {
            case 'student':
                // hanya pemilik laporan
                if ($report->application->student->user_id !== $user->id) {
                    abort(403);
                }
                break;

            case 'teacher':
                // untuk sederhana: izinkan semua guru
                // (kalau mau lebih ketat bisa dicek jurusan)
                break;

            case 'industry_supervisor':
                // hanya industri yang menerima siswa tsb
                $industry = $user->industry;
                if (! $industry || $report->application->industry_id !== $industry->id) {
                    abort(403);
                }
                break;

            case 'admin':
                // admin boleh lihat semua
                break;

            default:
                abort(403);
        }

        if (! $report->file_path) {
            abort(404, 'File laporan tidak ditemukan.');
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($report->file_path)) {
            abort(404, 'File laporan tidak ditemukan.');
        }

        $fullPath = $disk->path($report->file_path);

        // tampilkan file (PDF/DOC/DOCX di-download / dibuka di viewer)
        return response()->file($fullPath);
        // kalau mau dipaksa download:
        // return response()->download($fullPath);
    }
}
