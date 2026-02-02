<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\IndustryQuota;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $industry = Auth::user()->industry;

        if (!$industry) {
            return redirect()->route('industry.profile.edit');
        }

        $industryId = $industry->id;
        $today = now()->toDateString();

        $waitingConfirm = InternshipApplication::where('industry_id', $industryId)
            ->where('status', InternshipApplication::STATUS_ASSIGNED_BY_ADMIN)
            ->count();

        $acceptedInterns = InternshipApplication::where('industry_id', $industryId)
            ->where('status', InternshipApplication::STATUS_ACCEPTED)
            ->count();

        $activeQuotas = IndustryQuota::where('industry_id', $industryId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        // optional: kalau status logbook ada 'pending'
        $pendingLogbooks = LogbookEntry::whereIn(
                'internship_application_id',
                InternshipApplication::select('id')->where('industry_id', $industryId)
            )
            ->where('status', 'pending')
            ->count();

        return view('industry.dashboard', compact(
            'waitingConfirm',
            'acceptedInterns',
            'activeQuotas',
            'pendingLogbooks'
        ));
    }
}
