<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user?->role?->name;

        return match ($role) {
            'admin'              => redirect()->route('admin.dashboard'),
            'teacher'            => redirect()->route('teacher.dashboard'),
            'industry_supervisor'=> $user->industry
                                    ? redirect()->route('industry.dashboard')
                                    : redirect()->route('industry.profile.edit'),
            'student'            => redirect()->route('student.dashboard'),
            default              => redirect('/'),
        };
    }
}
