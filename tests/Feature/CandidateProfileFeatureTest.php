<?php

namespace Tests\Feature;

use App\Models\CandidateProfile;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CandidateProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_candidate_page_is_hidden_from_navigation_and_noindexed(): void
    {
        $this->get(route('candidate-profiles.create'))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Биди Хонорарец');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('/bidi-honorarec', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/bidi-honorarec', false);
    }

    public function test_valid_submission_creates_a_separate_candidate_profile_with_consent_evidence(): void
    {
        $jobApplicationsBefore = JobApplication::query()->count();

        $this->post(route('candidate-profiles.store'), $this->validPayload())
            ->assertRedirect(route('candidate-profiles.create'))
            ->assertSessionHas('candidate_profile_status');

        $candidate = CandidateProfile::query()->sole();
        $this->assertSame('38970214325', $candidate->phone_normalized);
        $this->assertSame(['daily', 'weekend'], $candidate->engagement_types);
        $this->assertSame(['hospitality', 'logistics_transport'], $candidate->work_categories);
        $this->assertSame(CandidateProfile::STATUS_NEW, $candidate->status);
        $this->assertSame(config('privacy.policy_version'), $candidate->privacy_policy_version);
        $this->assertNotNull($candidate->privacy_acknowledged_at);
        $this->assertNotNull($candidate->employer_contact_consented_at);
        $this->assertSame($jobApplicationsBefore, JobApplication::query()->count());
    }

    public function test_validation_rejects_invalid_values_and_missing_explicit_consent(): void
    {
        $payload = $this->validPayload([
            'gender' => 'unexpected',
            'engagement_types' => ['not_allowed'],
            'work_categories' => ['invalid_category'],
            'preferred_radius' => '100',
            'privacy_consent' => null,
        ]);

        $this->from(route('candidate-profiles.create'))->post(route('candidate-profiles.store'), $payload)
            ->assertRedirect(route('candidate-profiles.create'))
            ->assertSessionHasErrors(['gender', 'engagement_types.0', 'work_categories.0', 'preferred_radius', 'privacy_consent']);

        $this->assertDatabaseCount('candidate_profiles', 0);
    }

    public function test_duplicate_phone_and_honeypot_are_rejected_without_creating_records(): void
    {
        $this->post(route('candidate-profiles.store'), $this->validPayload())->assertRedirect();
        $this->post(route('candidate-profiles.store'), $this->validPayload(['email' => 'other@example.com']))
            ->assertSessionHasErrors('phone');
        $this->post(route('candidate-profiles.store'), $this->validPayload(['phone' => '070 333 444', 'website' => 'https://bot.example']))
            ->assertSessionHasErrors('form');

        $this->assertDatabaseCount('candidate_profiles', 1);
    }

    public function test_database_unique_constraint_prevents_duplicate_normalized_phones(): void
    {
        CandidateProfile::query()->create($this->candidateAttributes());

        $this->expectException(QueryException::class);
        CandidateProfile::query()->create($this->candidateAttributes(['email' => 'different@example.com']));
    }

    public function test_candidate_submission_rate_limit_and_turnstile_behaviour(): void
    {
        config()->set('services.turnstile.enabled', true);
        config()->set('services.turnstile.secret_key', 'test-secret');

        Http::fake(['https://challenges.cloudflare.com/*' => Http::response(['success' => false])]);
        $this->post(route('candidate-profiles.store'), $this->validPayload(['turnstile_token' => 'invalid']))
            ->assertSessionHasErrors('form');

        Http::fake(['https://challenges.cloudflare.com/*' => Http::response([
            'success' => true, 'action' => 'candidate_registration', 'hostname' => 'localhost',
        ])]);
        $this->post(route('candidate-profiles.store'), $this->validPayload(['turnstile_token' => 'valid']))->assertRedirect();
        Http::assertSentCount(1);

        config()->set('services.turnstile.enabled', false);
        foreach (range(1, 3) as $number) {
            $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.'.$number])
                ->post(route('candidate-profiles.store'), $this->validPayload(['phone' => '070 40'.$number.' 000']))->assertRedirect();
        }

        foreach (range(1, 5) as $number) {
            $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
                ->post(route('candidate-profiles.store'), $this->validPayload(['phone' => '070 50'.$number.' 000']))->assertRedirect();
        }
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
            ->post(route('candidate-profiles.store'), $this->validPayload(['phone' => '070 509 999']))->assertStatus(429);
    }

    public function test_admin_candidate_access_filters_and_management_are_protected(): void
    {
        $candidate = CandidateProfile::query()->create($this->candidateAttributes([
            'first_name' => 'Ана', 'city' => 'Скопје', 'neighbourhood' => 'Карпош', 'gender' => 'female',
            'date_of_birth' => now()->subYears(25)->toDateString(), 'driving_status' => 'active_driver',
            'engagement_types' => ['all'], 'work_categories' => ['hospitality'],
        ]));
        CandidateProfile::query()->create($this->candidateAttributes(['phone_normalized' => '38970111222', 'phone' => '070 111 222', 'first_name' => 'Борис', 'city' => 'Битола']));

        $this->get(route('admin.candidates.index'))->assertRedirect(route('admin.login'));

        $companyUser = User::factory()->create(['is_admin' => false]);
        $this->actingAs($companyUser)->get(route('admin.candidates.index'))->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get(route('admin.candidates.index', [
            'city' => 'Скопје', 'gender' => 'female', 'min_age' => 24,
            'engagement_types' => ['daily'], 'work_categories' => ['hospitality'],
        ]))->assertOk()->assertSee('Ана')->assertDontSee('Борис');

        $this->actingAs($admin)->put(route('admin.candidates.update', $candidate), [
            'first_name' => 'Ана', 'last_name' => 'Петрова', 'city' => 'Скопје', 'neighbourhood' => 'Карпош',
            'exact_address' => 'Тест адреса', 'email' => 'ana@example.com', 'gender' => 'female',
            'date_of_birth' => now()->subYears(25)->toDateString(), 'driving_status' => 'active_driver',
            'preferred_radius' => '10', 'current_employment_status' => 0, 'status' => 'active', 'admin_notes' => 'Проверен профил', 'last_confirmed' => 1,
        ])->assertSessionHas('status');
        $this->assertDatabaseHas('candidate_profiles', ['id' => $candidate->id, 'status' => 'active', 'admin_notes' => 'Проверен профил']);

        $this->actingAs($admin)->delete(route('admin.candidates.destroy', $candidate))->assertRedirect();
        $this->assertSoftDeleted('candidate_profiles', ['id' => $candidate->id]);
        $this->actingAs($admin)->post(route('admin.candidates.restore', $candidate->id))->assertRedirect();
        $this->assertDatabaseHas('candidate_profiles', ['id' => $candidate->id, 'deleted_at' => null]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_replace($this->candidateAttributes(), ['privacy_consent' => 1, 'website' => '', 'turnstile_token' => null], $overrides);
    }

    private function candidateAttributes(array $overrides = []): array
    {
        return array_replace([
            'first_name' => 'Ивана', 'last_name' => 'Петрова', 'gender' => 'female', 'date_of_birth' => '1995-06-15',
            'phone' => '070 214 325', 'phone_normalized' => '38970214325', 'email' => 'ivana@example.com',
            'city' => 'Скопје', 'neighbourhood' => 'Центар', 'exact_address' => null, 'preferred_radius' => '10',
            'driving_status' => 'active_driver', 'current_employment_status' => false,
            'engagement_types' => ['daily', 'weekend'], 'work_categories' => ['hospitality', 'logistics_transport'],
            'other_work_preference' => null, 'additional_information' => 'Достапна за викенд.',
            'status' => CandidateProfile::STATUS_NEW, 'privacy_policy_version' => config('privacy.policy_version'),
            'privacy_acknowledged_at' => now(), 'employer_contact_consented_at' => now(),
        ], $overrides);
    }
}
