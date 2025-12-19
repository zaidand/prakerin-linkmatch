<?php

namespace App\Http\Controllers\Industry;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('industry.dashboard');
    }
}
