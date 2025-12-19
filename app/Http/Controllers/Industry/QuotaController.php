<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\IndustryQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotaController extends Controller
{
    public function index()
    {
        $industry = Auth::user()->industry;

        if (! $industry) {
            return redirect()
                ->route('industry.profile.edit')
                ->withErrors('Silakan lengkapi profil industri terlebih dahulu.');
        }

        $quotas = $industry->quotas()->orderByDesc('start_date')->paginate(10);

        return view('industry.quotas.index', compact('quotas', 'industry'));
    }

    public function create()
    {
        $industry = Auth::user()->industry;

        if (! $industry) {
            return redirect()
                ->route('industry.profile.edit')
                ->withErrors('Silakan lengkapi profil industri terlebih dahulu.');
        }

        return view('industry.quotas.create', compact('industry'));
    }

    public function store(Request $request)
    {
        $industry = Auth::user()->industry;

        $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'max_students' => 'required|integer|min:1',
            'criteria'     => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $industry->quotas()->create([
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'max_students' => $request->max_students,
            'criteria'     => $request->criteria,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('industry.quotas.index')
            ->with('success', 'Kuota prakerin berhasil dibuat.');
    }

    public function edit(IndustryQuota $quota)
    {
        $industry = Auth::user()->industry;

        if ($quota->industry_id !== $industry->id) {
            abort(403);
        }

        return view('industry.quotas.edit', compact('quota', 'industry'));
    }

    public function update(Request $request, IndustryQuota $quota)
    {
        $industry = Auth::user()->industry;

        if ($quota->industry_id !== $industry->id) {
            abort(403);
        }

        $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'max_students' => 'required|integer|min:1',
            'criteria'     => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $quota->update([
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'max_students' => $request->max_students,
            'criteria'     => $request->criteria,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('industry.quotas.index')
            ->with('success', 'Kuota prakerin berhasil diperbarui.');
    }

    public function destroy(IndustryQuota $quota)
    {
        $industry = Auth::user()->industry;

        if ($quota->industry_id !== $industry->id) {
            abort(403);
        }

        $quota->delete();

        return redirect()->route('industry.quotas.index')
            ->with('success', 'Kuota prakerin berhasil dihapus.');
    }
}
