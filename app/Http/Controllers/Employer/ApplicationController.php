<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $companyId = (int) $request->user()->company_id;
        $selectedJobId = $request->integer('job');

        $applications = JobApplication::with('jobListing')
            ->whereHas('jobListing', function (Builder $query) use ($companyId, $selectedJobId): void {
                $query->where('company_id', $companyId);

                if ($selectedJobId) {
                    $query->where('id', $selectedJobId);
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('employer.applications.index', [
            'applications' => $applications,
            'jobs' => JobListing::where('company_id', $companyId)->orderBy('title')->get(),
            'selectedJobId' => $selectedJobId,
        ]);
    }

    public function show(JobApplication $application): View
    {
        $application->load('jobListing.company');
        $this->authorizeApplication($application);

        return view('employer.applications.show', [
            'application' => $application,
        ]);
    }

    public function cv(JobApplication $application): StreamedResponse
    {
        $application->load('jobListing');
        $this->authorizeApplication($application);

        abort_if(blank($application->cv_path), 404);
        abort_unless(Storage::disk('public')->exists($application->cv_path), 404);

        return Storage::disk('public')->download(
            $application->cv_path,
            basename($application->cv_path),
        );
    }

    private function authorizeApplication(JobApplication $application): void
    {
        abort_unless(
            (int) $application->jobListing->company_id === (int) request()->user()->company_id,
            404
        );
    }
}
