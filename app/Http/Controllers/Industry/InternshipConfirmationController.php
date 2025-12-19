<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipConfirmationController extends Controller
{
    public function index()
    {
        $industry = Auth::user()->industry;

        if (! $industry) {
            abort(403, 'Profil industri belum diisi.');
        }

        $applications = $industry->applications()
            ->with(['student.user', 'student.major', 'quota'])
            ->where('status', InternshipApplication::STATUS_WAITING_INDUSTRY)
            ->orderBy('created_at')
            ->paginate(15);

        return view('industry.applications.index', compact('applications', 'industry'));
    }

    public function confirm(Request $request, InternshipApplication $application)
    {
        $this->authorize('confirm', $application);
        $industry = Auth::user()->industry;

        if (! $industry || $application->industry_id !== $industry->id) {
            abort(403);
        }

        $request->validate([
            'action'        => 'required|in:accept,reject',
            'industry_note' => 'nullable|string',
        ]);

        $status = $request->action === 'accept'
            ? InternshipApplication::STATUS_ACCEPTED
            : InternshipApplication::STATUS_REJECTED;

        $application->update([
            'status'                => $status,
            'industry_note'         => $request->industry_note,
            'industry_confirmed_at' => now(),
        ]);

        return redirect()->route('industry.applications.index')
            ->with('success', 'Keputusan industri telah disimpan.');
    }
}
