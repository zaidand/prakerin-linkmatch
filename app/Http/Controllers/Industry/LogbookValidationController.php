<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookValidationController extends Controller
{
    protected function getIndustry()
    {
        $industry = Auth::user()->industry;
        if (! $industry) {
            abort(403, 'Profil industri belum diisi.');
        }
        return $industry;
    }

    public function index()
    {
        $industry = $this->getIndustry();

        $logbooks = LogbookEntry::with(['application.student.user'])
            ->whereHas('application', function ($q) use ($industry) {
                $q->where('industry_id', $industry->id);
            })
            ->where('status', LogbookEntry::STATUS_WAITING)
            ->orderBy('log_date', 'asc')
            ->paginate(15);

        return view('industry.logbooks.index', compact('logbooks', 'industry'));
    }

    public function show(LogbookEntry $logbook)
    {
        $industry = $this->getIndustry();

        if ($logbook->application->industry_id !== $industry->id) {
            abort(403);
        }

        return view('industry.logbooks.show', compact('logbook', 'industry'));
    }

    public function validateEntry(Request $request, LogbookEntry $logbook)
    {
        $industry = $this->getIndustry();

        if ($logbook->application->industry_id !== $industry->id) {
            abort(403);
        }

        $request->validate([
            'action'          => 'required|in:approve,reject',
            'industry_comment'=> 'nullable|string',
        ]);

        $status = $request->action === 'approve'
            ? LogbookEntry::STATUS_APPROVED
            : LogbookEntry::STATUS_REJECTED;

        $logbook->update([
            'status'          => $status,
            'industry_comment'=> $request->industry_comment,
            'validated_at'    => now(),
        ]);

        return redirect()->route('industry.logbooks.index')
            ->with('success', 'Logbook telah divalidasi.');
    }

    public function showEvidence(LogbookEntry $logbook)
    {
    $industry = $this->getIndustry();

    // Pastikan logbook ini memang milik industri yang login
    if ($logbook->application->industry_id !== $industry->id) {
        abort(403);
    }

    // Pastikan ada path filenya
    if (! $logbook->evidence_path) {
        abort(404, 'File dokumentasi tidak ditemukan.');
    }

    // Cek di disk 'public' apakah file ada
    $disk = Storage::disk('public');

    if (! $disk->exists($logbook->evidence_path)) {
        abort(404, 'File dokumentasi tidak ditemukan.');
    }

    // Ambil path absolut ke file
    $fullPath = $disk->path($logbook->evidence_path);

    // Kirim file ke browser (bisa image / pdf)
    return response()->file($fullPath);
    // Kalau mau paksa download:
    // return response()->download($fullPath);
    }


}
