<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $selectedJobId = $request->integer('job');

        $applications = JobApplication::with('jobListing.company')
            ->when($selectedJobId, function (Builder $query) use ($selectedJobId): void {
                $query->where('job_listing_id', $selectedJobId);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'jobs' => JobListing::orderBy('title')->get(),
            'selectedJobId' => $selectedJobId,
        ]);
    }

    public function show(JobApplication $application): View
    {
        return view('admin.applications.show', [
            'application' => $application->load('jobListing.company'),
        ]);
    }

    public function cv(JobApplication $application): StreamedResponse
    {
        abort_if(blank($application->cv_path), 404);
        abort_unless(Storage::disk('public')->exists($application->cv_path), 404);

        return Storage::disk('public')->download(
            $application->cv_path,
            basename($application->cv_path),
        );
    }
}
