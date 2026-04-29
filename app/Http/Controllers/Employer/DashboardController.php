<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $companyId = (int) request()->user()->company_id;
        $company = Company::find($companyId);

        $jobsCount = JobListing::where('company_id', $companyId)->count();
        $applicationsCount = JobApplication::whereHas('jobListing', function ($query) use ($companyId): void {
            $query->where('company_id', $companyId);
        })->count();

        return view('employer.dashboard', [
            'stats' => [
                'jobs' => $jobsCount,
                'applications' => $applicationsCount,
            ],
            'company' => $company,
            'recentJobs' => JobListing::where('company_id', $companyId)
                ->latest()
                ->take(8)
                ->get(),
            'recentApplications' => JobApplication::with('jobListing')
                ->whereHas('jobListing', function ($query) use ($companyId): void {
                    $query->where('company_id', $companyId);
                })
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}
