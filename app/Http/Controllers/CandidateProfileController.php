<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateProfileRequest;
use App\Models\CandidateProfile;
use App\Models\Company;
use App\Models\JobListing;
use App\Services\TurnstileVerifier;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CandidateProfileController extends Controller
{
    public function create(): Response
    {
        $this->ensureCandidateProfilesTableExists();

        return response()->view('pages.candidate-profiles.create', [
            'title' => 'Биди Хонорарец | Honorarec.mk',
            'description' => 'Пријави се во приватната база на кандидати на Honorarec.mk.',
            'canonical' => route('candidate-profiles.create'),
            'footerStats' => $this->footerStats(),
            'turnstileEnabled' => (bool) config('services.turnstile.enabled'),
            'turnstileSiteKey' => config('services.turnstile.site_key'),
        ])->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function store(StoreCandidateProfileRequest $request, TurnstileVerifier $turnstileVerifier): RedirectResponse
    {
        $this->ensureCandidateProfilesTableExists();

        if (filled($request->input('website'))) {
            Log::warning('candidate_submission_rejected', ['reason' => 'honeypot']);

            return back()->withErrors(['form' => 'Податоците не можеа да се испратат. Обидете се повторно.'])->withInput();
        }

        if (! $turnstileVerifier->passes($request->input('turnstile_token'), $request)) {
            return back()->withErrors(['form' => 'Податоците не можеа да се потврдат. Обидете се повторно.'])->withInput();
        }

        $normalizedPhone = $request->normalizedPhone();

        if (CandidateProfile::query()->where('phone_normalized', $normalizedPhone)->exists()) {
            return back()->withErrors(['phone' => 'Профил со овој телефонски број веќе постои. Контактирајте го Honorarec.mk ако сакате да ги ажурирате податоците.'])->withInput();
        }

        try {
            $data = $request->validated();
            unset($data['privacy_consent'], $data['website'], $data['turnstile_token']);

            CandidateProfile::query()->create([
                ...$data,
                'email' => mb_strtolower(trim((string) $request->input('email'))),
                'phone_normalized' => $normalizedPhone,
                'status' => CandidateProfile::STATUS_NEW,
                'privacy_policy_version' => config('privacy.policy_version'),
                'privacy_acknowledged_at' => now(),
                'employer_contact_consented_at' => now(),
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return back()->withErrors(['phone' => 'Профил со овој телефонски број веќе постои. Контактирајте го Honorarec.mk ако сакате да ги ажурирате податоците.'])->withInput();
        }

        return redirect()->route('candidate-profiles.create')->with(
            'candidate_profile_status',
            'Твоите податоци се успешно испратени. Ќе те контактираме кога ќе се појави соодветна можност за работа.',
        );
    }

    private function ensureCandidateProfilesTableExists(): void
    {
        abort_unless(Schema::hasTable('candidate_profiles'), 503);
    }

    private function footerStats(): array
    {
        return [
            ['value' => Schema::hasTable('job_listings') && Schema::hasTable('companies') ? JobListing::where('status', JobListing::STATUS_ACTIVE)->count() : 0, 'label' => 'Огласи за работа'],
            ['value' => Schema::hasTable('companies') ? Company::count() : 0, 'label' => 'Компании'],
        ];
    }
}
