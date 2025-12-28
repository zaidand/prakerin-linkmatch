<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;

class IntroductionLetterController extends Controller
{
    public function show(Request $request, InternshipApplication $application)
    {
        // Surat hanya boleh dibuat setelah admin assign (atau sudah accepted)
        if (!in_array($application->status, [
            InternshipApplication::STATUS_ASSIGNED_BY_ADMIN,
            InternshipApplication::STATUS_ACCEPTED,
        ])) {
            abort(403, 'Surat pengantar hanya tersedia setelah penempatan ditetapkan admin.');
        }

        $application->load([
            'student.user',
            'student.major',
            'industry',
            'quota',
        ]);

        // kalau url pakai ?print=1 -> otomatis buka dialog print
        $autoPrint = $request->boolean('print');

        return view('admin.applications.letter', compact('application', 'autoPrint'));
    }
}
