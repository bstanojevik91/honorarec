<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerApplicationsPaginationTranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagination_translation_keys_resolve_to_macedonian_labels_for_the_active_locale(): void
    {
        $this->assertSame('en', app()->getLocale());
        $this->assertSame('en', config('app.fallback_locale'));
        $this->assertSame('Претходнo', __('pagination.previous'));
        $this->assertSame('Следнo', __('pagination.next'));
    }

    public function test_employer_applications_pagination_uses_translated_labels_instead_of_raw_keys(): void
    {
        $company = Company::create([
            'name' => 'Employer Company',
            'email' => 'employer-applications@test.mk',
            'phone' => '070123456',
            'description' => 'Employer company',
        ]);

        $user = User::create([
            'name' => 'Employer User',
            'email' => 'employer-user@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас за апликации',
            'slug' => 'oglas-za-aplikacii',
            'description' => 'Опис на оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'engagement_type' => JobListing::ENGAGEMENT_PART_TIME,
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        foreach (range(1, 25) as $index) {
            JobApplication::create([
                'job_listing_id' => $job->id,
                'full_name' => "Кандидат {$index}",
                'phone' => '07'.str_pad((string) $index, 7, '0', STR_PAD_LEFT),
                'phone_normalized' => '3897'.str_pad((string) $index, 7, '0', STR_PAD_LEFT),
                'city' => 'Скопје',
                'message' => 'Заинтересиран сум.',
                'privacy_policy_version' => '2026-08-01',
                'privacy_acknowledged_at' => now(),
            ]);
        }

        $firstPage = $this->actingAs($user)->get(route('employer.applications.index'));

        $firstPage->assertOk()
            ->assertSeeText('Претходнo')
            ->assertSeeText('Следнo')
            ->assertDontSeeText('pagination.previous')
            ->assertDontSeeText('pagination.next');

        $laterPage = $this->actingAs($user)->get(route('employer.applications.index', ['page' => 2]));

        $laterPage->assertOk()
            ->assertSeeText('Претходнo')
            ->assertSeeText('Следнo')
            ->assertDontSeeText('pagination.previous')
            ->assertDontSeeText('pagination.next');
    }
}
