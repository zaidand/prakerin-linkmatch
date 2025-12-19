<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalGrade;
use Illuminate\Http\Request;

class FinalGradeReportController extends Controller
{
    public function index()
    {
        $grades = FinalGrade::with(['application.student.user', 'application.student.major', 'application.industry'])
            ->orderBy('final_score', 'desc')
            ->paginate(25);

        return view('admin.final_grades.index', compact('grades'));
    }

    public function exportCsv()
    {
        $grades = FinalGrade::with(['application.student.user', 'application.student.major', 'application.industry', 'application.finalReport'])
            ->orderBy('final_score', 'desc')
            ->get();

        $filename = 'rekap_nilai_prakerin_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($grades) {
            $handle = fopen('php://output', 'w');

            // header kolom
            fputcsv($handle, [
                'Nama Siswa',
                'Jurusan',
                'Industri',
                'Nilai Industri',
                'Nilai Laporan',
                'Nilai Kehadiran',
                'Nilai Akhir',
                'Grade',
            ]);

            foreach ($grades as $g) {
                $app = $g->application;
                fputcsv($handle, [
                    $app->student->user->name ?? '',
                    $app->student->major->name ?? '',
                    $app->industry->name ?? '',
                    $g->industry_score,
                    $g->report_score,
                    $g->attendance_score,
                    $g->final_score,
                    $g->grade_letter,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
