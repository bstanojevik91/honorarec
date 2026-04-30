<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobListingRequest;
use App\Http\Requests\Admin\UpdateJobListingRequest;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(): View
    {
        return view('admin.jobs.index', [
            'jobs' => JobListing::with('company')->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.jobs.create', [
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    public function store(StoreJobListingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $this->resolveCompanyId($request, $data);
        $data['featured'] = $request->boolean('featured');
        $data['status'] = $data['status'] ?? 'active';
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->onlyJobFields($data);
        $data = $this->normalizeJobFields($data);

        JobListing::create($data);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Огласот е успешно додаден.');
    }

    public function edit(JobListing $job): View
    {
        return view('admin.jobs.edit', [
            'job' => $job,
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateJobListingRequest $request, JobListing $job): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $this->resolveCompanyId($request, $data);
        $data['featured'] = $request->boolean('featured');
        $data['status'] = $data['status'] ?? 'active';
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data = $this->onlyJobFields($data);
        $data = $this->normalizeJobFields($data);

        $job->update($data);

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
            'call_phone' => $data['new_company_call_phone'] ?? null,
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
            'contact_phone',
            'call_enabled',
            'featured',
            'status',
            'expires_at',
        ])->all();
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
