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
            'industry_supervisor'=> redirect()->route('industry.dashboard'),
            'student'            => redirect()->route('student.dashboard'),
            default              => redirect('/'),
        };
    }
}
