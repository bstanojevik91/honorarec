<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreEmployerJobRequest;
use App\Http\Requests\Employer\UpdateEmployerJobRequest;
use App\Models\JobListing;
use App\Models\Tag;
use App\Support\TagSystem;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        return view('employer.jobs.index', [
            'jobs' => $this->jobListingQuery()
                ->where('company_id', request()->user()->company_id)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('employer.jobs.create', [
            'availableTags' => $this->availableTags(),
        ]);
    }

    public function store(StoreEmployerJobRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];

        $data = collect($validated)->only([
            'title',
            'slug',
            'description',
            'daily_pay',
            'location',
            'category',
            'engagement_type',
            'featured',
        ])->when(
            ! Schema::hasColumn('job_listings', 'engagement_type'),
            fn ($collection) => $collection->except('engagement_type')
        )->all();

        $data['company_id'] = request()->user()->company_id;
        $data['featured'] = $request->boolean('featured');
        $data['status'] = JobListing::STATUS_PENDING;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->normalizeJobFields($data);
        $data['expires_at'] = $this->defaultExpiryDate();

        $job = JobListing::create($data);
        $this->syncTags($job, $tagIds);

        return redirect()
            ->route('employer.jobs.index')
            ->with('status', 'Огласот е испратен на одобрување од администратор.');
    }

    public function edit(JobListing $job): View
    {
        $this->authorizeCompanyJob($job);

        if (TagSystem::enabled()) {
            $job->loadMissing('tags');
        }

        return view('employer.jobs.edit', [
            'job' => $job,
            'availableTags' => $this->availableTags(),
        ]);
    }

    public function update(UpdateEmployerJobRequest $request, JobListing $job): RedirectResponse
    {
        $this->authorizeCompanyJob($job);

        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];

        $data = collect($validated)->only([
            'title',
            'slug',
            'description',
            'daily_pay',
            'location',
            'category',
            'engagement_type',
            'featured',
        ])->when(
            ! Schema::hasColumn('job_listings', 'engagement_type'),
            fn ($collection) => $collection->except('engagement_type')
        )->all();

        $data['featured'] = $request->boolean('featured');
        $data['status'] = JobListing::STATUS_PENDING;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->normalizeJobFields($data);

        $job->update($data);
        $this->syncTags($job, $tagIds);

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

    public function updateEngagementType(Request $request, JobListing $job): RedirectResponse
    {
        $this->authorizeCompanyJob($job);

        $data = $request->validate([
            'engagement_type' => ['nullable', Rule::in(JobListing::engagementTypeOptions())],
        ], [], [
            'engagement_type' => 'вид на работен ангажман',
        ]);

        $job->update([
            'engagement_type' => $data['engagement_type'] ?? null,
            'status' => JobListing::STATUS_PENDING,
        ]);

        return back()->with('status', 'Работниот ангажман е ажуриран и огласот е испратен на повторно одобрување.');
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
        $data['engagement_type'] = $data['engagement_type'] ?? null;

        return $data;
    }

    private function defaultExpiryDate(): Carbon
    {
        return now()->addDays(30)->startOfDay();
    }

    private function jobListingQuery()
    {
        $query = JobListing::query();

        if (TagSystem::enabled()) {
            $query->with('tags');
        }

        return $query;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Tag>
     */
    private function availableTags(): Collection
    {
        if (! TagSystem::enabled()) {
            return collect();
        }

        return Tag::query()->orderBy('name')->get();
    }

    /**
     * @param  array<int, int>  $tagIds
     */
    private function syncTags(JobListing $job, array $tagIds): void
    {
        if (! TagSystem::enabled()) {
            return;
        }

        $job->tags()->sync($tagIds);
    }
}
