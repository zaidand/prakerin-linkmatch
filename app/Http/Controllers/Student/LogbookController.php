<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    protected function getActiveApplication()
    {
        $student = Auth::user()->student;

        return InternshipApplication::where('student_id', $student->id)
            ->where('status', InternshipApplication::STATUS_ACCEPTED)
            ->with('industry')
            ->firstOrFail();
    }

    public function index()
    {
        $application = $this->getActiveApplication();

        $logbooks = $application->logbooks()
            ->orderByDesc('log_date')
            ->paginate(10);

        return view('student.logbooks.index', compact('application', 'logbooks'));
    }

    public function create()
    {
        $application = $this->getActiveApplication();

        return view('student.logbooks.create', compact('application'));
    }

    public function store(Request $request)
    {
        $application = $this->getActiveApplication();

        $request->validate([
            'log_date'            => 'required|date',
            'check_in_time'       => 'nullable|date_format:H:i',
            'check_out_time'      => 'nullable|date_format:H:i',
            'activity_description'=> 'required|string',
            'tools_used'          => 'nullable|string',
            'competencies'        => 'nullable|string',
            'evidence'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('logbooks', 'public');
        }

        LogbookEntry::create([
            'internship_application_id' => $application->id,
            'log_date'                  => $request->log_date,
            'check_in_time'             => $request->check_in_time,
            'check_out_time'            => $request->check_out_time,
            'activity_description'      => $request->activity_description,
            'tools_used'                => $request->tools_used,
            'competencies'              => $request->competencies,
            'evidence_path'             => $evidencePath,
            'status'                    => LogbookEntry::STATUS_WAITING,
        ]);

        return redirect()->route('student.logbooks.index')
            ->with('success', 'Logbook berhasil disimpan dan menunggu validasi pembimbing lapangan.');
    }
}
