<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobListingRequest;
use App\Http\Requests\Admin\UpdateJobListingRequest;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\Tag;
use App\Support\TagSystem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(): View
    {
        return view('admin.jobs.index', [
            'jobs' => $this->jobListingQuery()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.jobs.create', [
            'companies' => Company::orderBy('name')->get(),
            'availableTags' => $this->availableTags(),
        ]);
    }

    public function store(StoreJobListingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        $data['company_id'] = $this->resolveCompanyId($request, $data);
        $data['featured'] = $request->boolean('featured');
        $data['status'] = $data['status'] ?? 'active';
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->onlyJobFields($data);
        $data = $this->normalizeJobFields($data);

        $job = JobListing::create($data);
        $this->syncTags($job, $tagIds);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е успешно додаден.');
    }

    public function edit(JobListing $job): View
    {
        if (TagSystem::enabled()) {
            $job->loadMissing('tags');
        }

        return view('admin.jobs.edit', [
            'job' => $job,
            'companies' => Company::orderBy('name')->get(),
            'availableTags' => $this->availableTags(),
        ]);
    }

    public function update(UpdateJobListingRequest $request, JobListing $job): RedirectResponse
    {
        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        $data['company_id'] = $this->resolveCompanyId($request, $data);
        $data['featured'] = $request->boolean('featured');
        $data['status'] = $data['status'] ?? 'active';
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->onlyJobFields($data);
        $data = $this->normalizeJobFields($data);

        $job->update($data);
        $this->syncTags($job, $tagIds);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е успешно ажуриран.');
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е избришан.');
    }

    public function updateEngagementType(Request $request, JobListing $job): RedirectResponse
    {
        $data = $request->validate([
            'engagement_type' => ['nullable', Rule::in(JobListing::engagementTypeOptions())],
        ], [], [
            'engagement_type' => 'вид на работен ангажман',
        ]);

        $job->update([
            'engagement_type' => $data['engagement_type'] ?? null,
        ]);

        return back()->with('status', 'Работниот ангажман е успешно ажуриран.');
    }

    public function approve(JobListing $job): RedirectResponse
    {
        $job->update([
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е одобрен и објавен.');
    }

    public function reject(JobListing $job): RedirectResponse
    {
        $job->update([
            'status' => JobListing::STATUS_REJECTED,
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е одбиен.');
    }

    /**
     * @param array<string, mixed> $data
     */
    private function resolveCompanyId(StoreJobListingRequest|UpdateJobListingRequest $request, array $data): int
    {
        if (! empty($data['company_id'])) {
            return (int) $data['company_id'];
        }

        $companyData = [
            'name' => $data['new_company_name'],
            'phone' => $data['new_company_phone'] ?? '',
            'email' => $data['new_company_email'] ?? null,
            'description' => $data['new_company_description'] ?? null,
        ];

        if (blank($companyData['email'])) {
            $companyData['email'] = Str::slug($companyData['name']).'@honorarec.mk';
        }

        if ($request->hasFile('new_company_logo')) {
            $companyData['logo_path'] = $request->file('new_company_logo')->store('companies', 'public');
        }

        $company = Company::create($companyData);

        return $company->id;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function onlyJobFields(array $data): array
    {
        return collect($data)->only([
            'company_id',
            'title',
            'slug',
            'description',
            'daily_pay',
            'location',
            'category',
            'engagement_type',
            'featured',
            'status',
            'expires_at',
        ])->when(
            ! Schema::hasColumn('job_listings', 'engagement_type'),
            fn ($collection) => $collection->except('engagement_type')
        )->all();
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

    private function jobListingQuery()
    {
        $query = JobListing::query()->with('company');

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
