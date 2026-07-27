<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_route_is_public_and_returns_the_configured_content(): void
    {
        $response = $this->get(route('privacy.policy'));

        $response->assertOk()
            ->assertSee('Политика за приватност')
            ->assertSee(config('privacy.controller_name'))
            ->assertSee(config('privacy.contact_email'))
            ->assertSee('Верзија: '.config('privacy.policy_version'))
            ->assertSee('Последно ажурирање: '.config('privacy.last_updated'));
    }

    public function test_job_application_form_contains_privacy_notice_and_policy_link_without_checkbox(): void
    {
        $job = $this->createActiveJob();

        $response = $this->get(route('jobs.show', $job->slug));

        $response->assertOk()
            ->assertSee(route('privacy.policy'), false)
            ->assertSee('Политиката за приватност')
            ->assertSeeInOrder([
                'Политиката за приватност',
                'Испрати апликација',
            ], false)
            ->assertDontSee('type="checkbox"', false)
            ->assertDontSee('name="privacy"', false);
    }

    public function test_existing_application_with_null_privacy_fields_remains_readable(): void
    {
        $job = $this->createActiveJob();

        $application = JobApplication::query()->create([
            'job_listing_id' => $job->id,
            'full_name' => 'Стар Кандидат',
            'phone' => '070000000',
            'phone_normalized' => '38970000000',
            'city' => 'Скопје',
            'message' => 'Стара апликација',
            'cv_path' => null,
            'privacy_policy_version' => null,
            'privacy_acknowledged_at' => null,
        ]);

        $freshApplication = JobApplication::query()->findOrFail($application->id);

        $this->assertNull($freshApplication->privacy_policy_version);
        $this->assertNull($freshApplication->privacy_acknowledged_at);
        $this->assertSame('Стар Кандидат', $freshApplication->full_name);
    }

    private function createActiveJob(): JobListing
    {
        $company = Company::create([
            'name' => 'Privacy Company',
            'email' => 'privacy@test.mk',
            'phone' => '070123456',
            'description' => 'Company for privacy tests.',
        ]);

        return JobListing::create([
            'company_id' => $company->id,
            'title' => 'Privacy Job',
            'slug' => 'privacy-job',
            'description' => 'Опис на оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);
    }
}
