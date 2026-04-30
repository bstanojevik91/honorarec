<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'jobs' => JobListing::count(),
                'companies' => Company::count(),
                'applications' => JobApplication::count(),
            ],
            'recentApplications' => JobApplication::with('jobListing.company')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}
