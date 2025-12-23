<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $waitingAssign = InternshipApplication::where('status', InternshipApplication::STATUS_APPROVED_BY_TEACHER)->count();

        $waitingIndustry = InternshipApplication::where('status', InternshipApplication::STATUS_ASSIGNED_BY_ADMIN)->count();

        $accepted = InternshipApplication::where('status', InternshipApplication::STATUS_ACCEPTED)->count();

        $rejected = InternshipApplication::where('status', InternshipApplication::STATUS_REJECTED)->count();

        $pendingUsers = User::where('status', 'pending')->count();
        $activeUsers  = User::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'waitingAssign',
            'waitingIndustry',
            'accepted',
            'rejected',
            'pendingUsers',
            'activeUsers'
        ));
    }
}
