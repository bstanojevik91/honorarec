<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Support\PhoneNormalizer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidateProfileController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureTableExists();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'], 'city' => ['nullable', 'string', 'max:100'],
            'neighbourhood' => ['nullable', 'string', 'max:100'], 'gender' => ['nullable', Rule::in(array_keys(CandidateProfile::genderOptions()))],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:120'], 'max_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'driving_status' => ['nullable', Rule::in(array_keys(CandidateProfile::drivingStatusOptions()))],
            'current_employment_status' => ['nullable', 'boolean'], 'preferred_radius' => ['nullable', Rule::in(array_keys(CandidateProfile::radiusOptions()))],
            'engagement_types' => ['nullable', 'array'], 'engagement_types.*' => [Rule::in(array_keys(CandidateProfile::engagementTypeOptions()))],
            'work_categories' => ['nullable', 'array'], 'work_categories.*' => [Rule::in(array_keys(CandidateProfile::workCategoryOptions()))],
            'status' => ['nullable', Rule::in(array_keys(CandidateProfile::statusOptions()))],
            'submitted_from' => ['nullable', 'date'], 'submitted_to' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'updated', 'confirmed', 'name', 'youngest', 'oldest_age'])],
        ]);

        $profiles = CandidateProfile::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['city'] ?? null, fn (Builder $query, string $value) => $query->where('city', $value))
            ->when($filters['neighbourhood'] ?? null, fn (Builder $query, string $value) => $query->where('neighbourhood', 'like', "%{$value}%"))
            ->when($filters['gender'] ?? null, fn (Builder $query, string $value) => $query->where('gender', $value))
            ->when($filters['driving_status'] ?? null, fn (Builder $query, string $value) => $query->where('driving_status', $value))
            ->when(array_key_exists('current_employment_status', $filters) && $filters['current_employment_status'] !== null, fn (Builder $query) => $query->where('current_employment_status', $filters['current_employment_status']))
            ->when($filters['preferred_radius'] ?? null, fn (Builder $query, string $value) => $query->where('preferred_radius', $value))
            ->when($filters['status'] ?? null, fn (Builder $query, string $value) => $query->where('status', $value))
            ->when($filters['min_age'] ?? null, fn (Builder $query, int $age) => $query->whereDate('date_of_birth', '<=', Carbon::today()->subYears($age)))
            ->when($filters['max_age'] ?? null, fn (Builder $query, int $age) => $query->whereDate('date_of_birth', '>=', Carbon::today()->subYears($age + 1)->addDay()))
            ->when($filters['submitted_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['submitted_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->when($filters['engagement_types'] ?? [], function (Builder $query, array $values): void {
                $query->where(function (Builder $query) use ($values): void {
                    foreach ($values as $value) {
                        $query->orWhereJsonContains('engagement_types', $value)->orWhereJsonContains('engagement_types', 'all');
                    }
                });
            })
            ->when($filters['work_categories'] ?? [], function (Builder $query, array $values): void {
                $query->where(function (Builder $query) use ($values): void {
                    foreach ($values as $value) {
                        $query->orWhereJsonContains('work_categories', $value);
                    }
                });
            });

        match ($filters['sort'] ?? 'newest') {
            'oldest' => $profiles->oldest(), 'updated' => $profiles->orderByDesc('updated_at'),
            'confirmed' => $profiles->orderByDesc('last_confirmed_at'), 'name' => $profiles->orderBy('last_name')->orderBy('first_name'),
            'youngest' => $profiles->orderByDesc('date_of_birth'), 'oldest_age' => $profiles->orderBy('date_of_birth'),
            default => $profiles->latest(),
        };

        return view('admin.candidates.index', [
            'profiles' => $profiles->paginate(20)->withQueryString(), 'filters' => $filters,
            'cities' => CandidateProfile::query()->select('city')->distinct()->orderBy('city')->pluck('city'),
        ]);
    }

    public function show(CandidateProfile $candidate): View
    {
        $this->ensureTableExists();

        return view('admin.candidates.show', ['candidate' => $candidate]);
    }

    public function update(Request $request, CandidateProfile $candidate): RedirectResponse
    {
        $this->ensureTableExists();
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'], 'last_name' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'], 'neighbourhood' => ['required', 'string', 'max:100'],
            'exact_address' => ['nullable', 'string', 'max:255'], 'email' => ['required', 'email:rfc', 'max:255'],
            'gender' => ['required', Rule::in(array_keys(CandidateProfile::genderOptions()))],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'driving_status' => ['required', Rule::in(array_keys(CandidateProfile::drivingStatusOptions()))],
            'current_employment_status' => ['required', 'boolean'], 'preferred_radius' => ['required', Rule::in(array_keys(CandidateProfile::radiusOptions()))],
            'status' => ['required', Rule::in(array_keys(CandidateProfile::statusOptions()))], 'admin_notes' => ['nullable', 'string', 'max:5000'],
            'last_confirmed' => ['nullable', 'boolean'],
        ]);
        $data['email'] = mb_strtolower(trim($data['email']));
        $data['last_confirmed_at'] = $request->boolean('last_confirmed') ? now() : $candidate->last_confirmed_at;

        if ($data['status'] === CandidateProfile::STATUS_WITHDRAWN && $candidate->consent_withdrawn_at === null) {
            $data['consent_withdrawn_at'] = now();
        }

        unset($data['last_confirmed']);
        $candidate->update($data);

        return back()->with('status', 'Податоците за кандидатот се ажурирани.');
    }

    public function destroy(CandidateProfile $candidate): RedirectResponse
    {
        $this->ensureTableExists();
        $candidate->delete();

        return redirect()->route('admin.candidates.index')->with('status', 'Кандидатот е избришан.');
    }

    public function restore(int $candidate): RedirectResponse
    {
        $this->ensureTableExists();
        CandidateProfile::withTrashed()->findOrFail($candidate)->restore();

        return redirect()->route('admin.candidates.index')->with('status', 'Кандидатот е вратен.');
    }

    private function ensureTableExists(): void
    {
        abort_unless(Schema::hasTable('candidate_profiles'), 503);
    }
}
