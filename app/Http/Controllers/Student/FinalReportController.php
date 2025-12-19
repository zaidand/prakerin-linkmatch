<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FinalReport;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinalReportController extends Controller
{
    protected function getAcceptedApplication()
    {
        $student = Auth::user()->student;

        return InternshipApplication::where('student_id', $student->id)
            ->where('status', InternshipApplication::STATUS_ACCEPTED)
            ->with('finalReport', 'industry')
            ->firstOrFail();
    }

    public function index()
    {
        $application = $this->getAcceptedApplication();
        $report = $application->finalReport;

        return view('student.final_report.index', compact('application', 'report'));
    }

    public function store(Request $request)
    {
        $application = $this->getAcceptedApplication();

        $request->validate([
            'report_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'summary'     => 'nullable|string',
        ]);

        if ($application->finalReport) {
            return redirect()->route('student.final_report.index')
                ->withErrors('Laporan sudah pernah dikirim. Gunakan menu revisi bila diizinkan.');
        }

        $path = $request->file('report_file')->store('final_reports', 'public');

        FinalReport::create([
            'internship_application_id' => $application->id,
            'file_path'                 => $path,
            'summary'                   => $request->summary,
            'status'                    => FinalReport::STATUS_WAITING,
            'submitted_at'              => now(),
        ]);

        return redirect()->route('student.final_report.index')
            ->with('success', 'Laporan akhir berhasil diunggah. Menunggu penilaian Guru Pembimbing.');
    }

    public function update(Request $request)
    {
        $application = $this->getAcceptedApplication();
        $report = $application->finalReport;

        if (! $report) {
            abort(404);
        }

        if ($report->status !== FinalReport::STATUS_REVISION) {
            return redirect()->route('student.final_report.index')
                ->withErrors('Laporan tidak dalam status revisi.');
        }

        $request->validate([
            'report_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'summary'     => 'nullable|string',
        ]);

        // hapus file lama
        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $path = $request->file('report_file')->store('final_reports', 'public');

        $report->update([
            'file_path'    => $path,
            'summary'      => $request->summary,
            'status'       => FinalReport::STATUS_WAITING,
            'submitted_at' => now(),
            'teacher_score'=> null,
            'teacher_comment' => null,
            'graded_at'    => null,
        ]);

        return redirect()->route('student.final_report.index')
            ->with('success', 'Revisi laporan berhasil diunggah.');
    }
}
