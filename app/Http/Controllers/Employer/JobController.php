<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreEmployerJobRequest;
use App\Http\Requests\Employer\UpdateEmployerJobRequest;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        return view('employer.jobs.index', [
            'jobs' => JobListing::where('company_id', request()->user()->company_id)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('employer.jobs.create');
    }

    public function store(StoreEmployerJobRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->only([
            'title',
            'slug',
            'description',
            'daily_pay',
            'location',
            'category',
            'contact_phone',
            'call_enabled',
            'featured',
            'expires_at',
        ])->all();

        $data['company_id'] = request()->user()->company_id;
        $data['featured'] = $request->boolean('featured');
        $data['status'] = JobListing::STATUS_PENDING;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->normalizeJobFields($data);

        JobListing::create($data);

        return redirect()
            ->route('employer.jobs.index')
            ->with('status', 'Огласот е испратен на одобрување од администратор.');
    }

    public function edit(JobListing $job): View
    {
        $this->authorizeCompanyJob($job);

        return view('employer.jobs.edit', [
            'job' => $job,
        ]);
    }

    public function update(UpdateEmployerJobRequest $request, JobListing $job): RedirectResponse
    {
        $this->authorizeCompanyJob($job);

        $data = collect($request->validated())->only([
            'title',
            'slug',
            'description',
            'daily_pay',
            'location',
            'category',
            'contact_phone',
            'call_enabled',
            'featured',
            'expires_at',
        ])->all();

        $data['featured'] = $request->boolean('featured');
        $data['status'] = JobListing::STATUS_PENDING;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->normalizeJobFields($data);

        $job->update($data);

        return redirect()
            ->route('employer.jobs.index')
            ->with('status', 'Промените на огласот се испратени на повторно одобрување.');
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        $this->authorizeCompanyJob($job);

        $job->delete();

        return redirect()
            ->route('employer.jobs.index')
            ->with('status', 'Огласот е избришан.');
    }

    private function authorizeCompanyJob(JobListing $job): void
    {
        abort_unless((int) $job->company_id === (int) request()->user()->company_id, 404);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function normalizeJobFields(array $data): array
    {
        $data['description'] = $data['description'] ?? '';
        $data['location'] = $data['location'] ?? '';
        $data['category'] = $data['category'] ?? '';
        $data['contact_phone'] = $data['contact_phone'] ?? null;
        $data['call_enabled'] = (bool) ($data['call_enabled'] ?? false);

        return $data;
    }
}
