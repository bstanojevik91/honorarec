<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCallClick;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JobCallClickTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const VISITOR_TOKEN = 'test-visitor-token';

    public function test_valid_call_click_request_for_active_listing_with_public_phone_creates_one_event(): void
    {
        $job = $this->createJobListing();

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())
            ->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 1);
        $this->assertDatabaseHas('job_call_clicks', [
            'job_listing_id' => $job->id,
        ]);
    }

    public function test_two_requests_from_same_session_inside_duplicate_window_create_only_one_event(): void
    {
        Carbon::setTestNow('2026-08-18 12:00:00');

        $job = $this->createJobListing();

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())->assertNoContent();
        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 1);

        Carbon::setTestNow();
    }

    public function test_later_legitimate_click_outside_duplicate_window_creates_another_event(): void
    {
        $job = $this->createJobListing();

        Carbon::setTestNow('2026-08-18 12:00:00');
        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())->assertNoContent();

        Carbon::setTestNow('2026-08-18 12:00:11');
        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 2);

        Carbon::setTestNow();
    }

    public function test_clicks_on_two_different_listings_are_counted_independently(): void
    {
        $firstJob = $this->createJobListing('first-job');
        $secondJob = $this->createJobListing('second-job');

        $this->post(route('jobs.call-click', $firstJob->slug), $this->trackingPayload())->assertNoContent();
        $this->post(route('jobs.call-click', $secondJob->slug), $this->trackingPayload())->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 2);
        $this->assertDatabaseHas('job_call_clicks', ['job_listing_id' => $firstJob->id]);
        $this->assertDatabaseHas('job_call_clicks', ['job_listing_id' => $secondJob->id]);
    }

    public function test_listing_without_public_phone_does_not_create_event(): void
    {
        $job = $this->createJobListing('no-phone-job', companyPhone: '');

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())
            ->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 0);
    }

    public function test_listing_using_no_public_call_token_does_not_create_event(): void
    {
        $job = $this->createJobListing('hidden-phone-job', companyPhone: '070123456 | __NO_PUBLIC_CALL__');

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())
            ->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 0);
    }

    public function test_inactive_listing_does_not_create_event(): void
    {
        $job = $this->createJobListing('paused-job', status: JobListing::STATUS_PAUSED);

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())
            ->assertNoContent();

        $this->assertDatabaseCount('job_call_clicks', 0);
    }

    public function test_endpoint_returns_harmless_no_content_when_tracking_table_is_missing(): void
    {
        $job = $this->createJobListing();

        Schema::dropIfExists('job_call_clicks');

        $this->post(route('jobs.call-click', $job->slug), $this->trackingPayload())
            ->assertNoContent();
    }

    public function test_deleting_job_listing_cascades_and_removes_click_events(): void
    {
        $job = $this->createJobListing();

        JobCallClick::create([
            'job_listing_id' => $job->id,
            'visitor_hash' => str_repeat('a', 64),
            'time_bucket' => 1,
            'dedupe_key' => str_repeat('b', 64),
        ]);

        $job->delete();

        $this->assertDatabaseCount('job_call_clicks', 0);
    }

    public function test_admin_jobs_page_displays_correct_call_click_count_for_each_listing(): void
    {
        $admin = $this->createAdminUser();
        $clickedJob = $this->createJobListing('clicked-job');
        $quietJob = $this->createJobListing('quiet-job');

        JobCallClick::create([
            'job_listing_id' => $clickedJob->id,
            'visitor_hash' => str_repeat('1', 64),
            'time_bucket' => 10,
            'dedupe_key' => str_repeat('a', 64),
        ]);

        JobCallClick::create([
            'job_listing_id' => $clickedJob->id,
            'visitor_hash' => str_repeat('2', 64),
            'time_bucket' => 11,
            'dedupe_key' => str_repeat('b', 64),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.jobs.index'))
            ->assertOk()
            ->assertSee('Кликови на „Повикај“: 2')
            ->assertSee('Кликови на „Повикај“: 0');
    }

    public function test_admin_jobs_page_still_shows_zero_when_tracking_table_is_temporarily_missing(): void
    {
        $admin = $this->createAdminUser();
        $job = $this->createJobListing();

        Schema::dropIfExists('job_call_clicks');

        $this->actingAs($admin)
            ->get(route('admin.jobs.index'))
            ->assertOk()
            ->assertSee($job->title)
            ->assertSee('Кликови на „Повикај“: 0');
    }

    public function test_non_admin_user_cannot_access_admin_jobs_page(): void
    {
        $employer = $this->createEmployerUser();

        $this->actingAs($employer)
            ->get(route('admin.jobs.index'))
            ->assertForbidden();
    }

    public function test_public_job_page_still_contains_functional_mobile_tel_links_and_existing_desktop_reveal_controls(): void
    {
        $job = $this->createJobListing();

        $this->get(route('jobs.show', $job->slug))
            ->assertOk()
            ->assertSee('href="tel:070123456"', false)
            ->assertSee('data-phone-reveal', false)
            ->assertSee('data-call-click-url="'.route('jobs.call-click', $job->slug).'"', false);
    }

    public function test_public_job_page_hides_call_controls_when_public_call_is_disabled(): void
    {
        $job = $this->createJobListing('hidden-public-call-page', companyPhone: '070123456 | __NO_PUBLIC_CALL__');

        $this->get(route('jobs.show', $job->slug))
            ->assertOk()
            ->assertDontSee('href="tel:070123456"', false)
            ->assertDontSee('data-collapsed-label="Прикажи телефонски број на компанијата"', false)
            ->assertDontSee('data-call-click-url="'.route('jobs.call-click', $job->slug).'"', false);
    }

    public function test_metric_is_not_rendered_publicly_or_in_employer_dashboard(): void
    {
        $job = $this->createJobListing();
        $employer = $this->createEmployerUser($job->company);

        $this->get(route('jobs.show', $job->slug))
            ->assertOk()
            ->assertDontSee('Кликови на „Повикај“');

        $this->actingAs($employer)
            ->get(route('employer.dashboard'))
            ->assertOk()
            ->assertDontSee('Кликови на „Повикај“');
    }

    public function test_existing_application_functionality_remains_unchanged(): void
    {
        $job = $this->createJobListing();

        $this->post(route('jobs.apply', $job->slug), [
            'full_name' => 'Тест Кандидат',
            'phone' => '071111111',
            'city' => 'Скопје',
            'message' => 'Сакам да аплицирам.',
        ])->assertRedirect(route('jobs.show', $job->slug).'#apply-form');

        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'full_name' => 'Тест Кандидат',
        ]);
        $this->assertSame(1, JobApplication::query()->count());
    }

    private function createJobListing(
        string $slug = 'job-call-click-test',
        string $companyPhone = '070123456',
        string $status = JobListing::STATUS_ACTIVE,
    ): JobListing {
        $company = Company::create([
            'name' => 'Компанија '.uniqid(),
            'email' => $slug.'@test.mk',
            'phone' => $companyPhone,
            'description' => 'Тест компанија',
        ]);

        return JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас '.uniqid(),
            'slug' => $slug,
            'description' => 'Опис на оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => $status,
        ]);
    }

    private function createAdminUser(): User
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin-call-clicks@test.mk',
            'password' => 'password123',
            'is_admin' => true,
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function createEmployerUser(?Company $company = null): User
    {
        $company ??= Company::create([
            'name' => 'Employer Company',
            'email' => 'employer-company@test.mk',
            'phone' => '070123456',
            'description' => 'Employer company',
        ]);

        $user = User::create([
            'name' => 'Employer User',
            'email' => 'employer-call-clicks@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    /**
     * @return array<string, string>
     */
    private function trackingPayload(): array
    {
        return [
            'visitor_token' => self::VISITOR_TOKEN,
        ];
    }
}
