<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\IndustryAssessment;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentController extends Controller
{
    protected function getIndustry()
    {
        $industry = Auth::user()->industry;
        if (! $industry) {
            return redirect()->route('industry.profile.edit');
        }
        return $industry;
    }

    public function index()
    {
        $industry = $this->getIndustry();

        $applications = InternshipApplication::with(['student.user', 'student.major', 'industryAssessment', 'quota'])
            ->where('industry_id', $industry->id)
            ->where('status', InternshipApplication::STATUS_ACCEPTED) // atau hampir selesai
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('industry.assessments.index', compact('applications', 'industry'));
    }

    public function edit(InternshipApplication $application)
    {
        $industry = $this->getIndustry();

        if ($application->industry_id !== $industry->id) {
            abort(403);
        }

        $assessment = $application->industryAssessment;

        return view('industry.assessments.edit', compact('application', 'assessment', 'industry'));
    }

    public function update(Request $request, InternshipApplication $application)
    {
        $industry = $this->getIndustry();

        if ($application->industry_id !== $industry->id) {
            abort(403);
        }

        $request->validate([
            'discipline'      => 'required|integer|min:0|max:100',
            'technical_skill' => 'required|integer|min:0|max:100',
            'teamwork'        => 'required|integer|min:0|max:100',
            'communication'   => 'required|integer|min:0|max:100',
            'responsibility'  => 'required|integer|min:0|max:100',
            'notes'           => 'nullable|string',
        ]);

        $overall = (
            $request->discipline +
            $request->technical_skill +
            $request->teamwork +
            $request->communication +
            $request->responsibility
        ) / 5;

        IndustryAssessment::updateOrCreate(
            ['internship_application_id' => $application->id],
            [
                'discipline'      => $request->discipline,
                'technical_skill' => $request->technical_skill,
                'teamwork'        => $request->teamwork,
                'communication'   => $request->communication,
                'responsibility'  => $request->responsibility,
                'overall_score'   => $overall,
                'notes'           => $request->notes,
            ]
        );

        return redirect()->route('industry.assessments.index')
            ->with('success', 'Penilaian siswa berhasil disimpan.');
    }
}
