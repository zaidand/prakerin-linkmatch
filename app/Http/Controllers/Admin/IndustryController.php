<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // optional filter

        $query = Industry::with(['user', 'majors'])->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $industries = $query->paginate(15);

        return view('admin.industries.index', compact('industries', 'status'));
    }

    public function show(Industry $industry)
    {
        $industry->load(['user', 'majors', 'quotas']);

        return view('admin.industries.show', compact('industry'));
    }

    public function updateStatus(Request $request, Industry $industry)
    {
        $request->validate([
            'status' => 'required|in:pending,active,rejected',
        ]);

        $industry->status = $request->status;
        $industry->save();

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Status industri berhasil diperbarui.');
    }
}
