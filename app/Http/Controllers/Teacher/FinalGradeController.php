<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\FinalGrade;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalGradeController extends Controller
{
    protected function getTeacher()
    {
        return Auth::user()->teacher;
    }

    public function index()
    {
        $teacher = $this->getTeacher();

        $applications = InternshipApplication::with([
                'student.user',
                'student.major',
                'industry',
                'industryAssessment',
                'finalReport',
                'finalGrade',
            ])
            ->where('status', InternshipApplication::STATUS_ACCEPTED)
            ->whereHas('student', function ($q) use ($teacher) {
                if ($teacher && $teacher->major_id) {
                    $q->where('major_id', $teacher->major_id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.final_grades.index', compact('applications', 'teacher'));
    }

    public function edit(InternshipApplication $application)
    {
        $teacher = $this->getTeacher();

        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }

        $application->load(['industryAssessment', 'finalReport', 'finalGrade', 'student.user', 'student.major', 'industry']);

        $grade = $application->finalGrade;

        // kalau belum ada, siapkan default di view
        return view('teacher.final_grades.edit', compact('application', 'grade'));
    }

    public function update(Request $request, InternshipApplication $application)
    {
        $teacher = $this->getTeacher();

        if ($teacher && $teacher->major_id && $application->student->major_id !== $teacher->major_id) {
            abort(403);
        }

        $request->validate([
            'industry_score'    => 'nullable|numeric|min:0|max:100',
            'report_score'      => 'nullable|numeric|min:0|max:100',
            'attendance_score'  => 'nullable|numeric|min:0|max:100',
            'weight_industry'   => 'required|integer|min:0|max:100',
            'weight_report'     => 'required|integer|min:0|max:100',
            'weight_attendance' => 'required|integer|min:0|max:100',
        ]);

        $wIndustry   = (int)$request->weight_industry;
        $wReport     = (int)$request->weight_report;
        $wAttendance = (int)$request->weight_attendance;

        if ($wIndustry + $wReport + $wAttendance !== 100) {
            return back()->withErrors('Total bobot harus 100%.')->withInput();
        }

        $industryScore   = $request->industry_score;
        $reportScore     = $request->report_score;
        $attendanceScore = $request->attendance_score;

        $finalScore =
            ($industryScore   ?? 0) * $wIndustry   / 100 +
            ($reportScore     ?? 0) * $wReport     / 100 +
            ($attendanceScore ?? 0) * $wAttendance / 100;

        // konversi ke huruf – aturan bisa kamu ganti
        $gradeLetter = null;
        if ($finalScore >= 85) {
            $gradeLetter = 'A';
        } elseif ($finalScore >= 75) {
            $gradeLetter = 'B';
        } elseif ($finalScore >= 65) {
            $gradeLetter = 'C';
        } elseif ($finalScore > 0) {
            $gradeLetter = 'D';
        }

        FinalGrade::updateOrCreate(
            ['internship_application_id' => $application->id],
            [
                'industry_score'    => $industryScore,
                'report_score'      => $reportScore,
                'attendance_score'  => $attendanceScore,
                'weight_industry'   => $wIndustry,
                'weight_report'     => $wReport,
                'weight_attendance' => $wAttendance,
                'final_score'       => $finalScore,
                'grade_letter'      => $gradeLetter,
            ]
        );

        return redirect()->route('teacher.final_grades.index')
            ->with('success', 'Nilai akhir prakerin berhasil disimpan.');
    }
}
