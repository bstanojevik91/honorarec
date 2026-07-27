<?php

namespace Tests\Feature;

use App\Mail\NewJobApplicationNotification;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationDuplicateProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_same_phone_can_apply_only_once_per_job_listing(): void
    {
        Storage::fake('public');
        Mail::fake();
        $this->prepareStageOneSchema();

        [$company, $job] = $this->createCompanyAndJob('promoter-duplicate-test');

        $firstResponse = $this->post(route('jobs.apply', $job->slug), [
            'full_name' => 'Петар Апликант',
            'phone' => '075 295 137',
            'city' => 'Скопје',
            'message' => 'Прва апликација.',
        ]);

        $firstResponse->assertRedirect(route('jobs.show', $job->slug).'#apply-form');

        $secondResponse = $this->post(route('jobs.apply', $job->slug), [
            'full_name' => 'Петар Апликант',
            'phone' => '+389 75 295 137',
            'city' => 'Скопје',
            'message' => 'Дупликат апликација.',
        ]);

        $secondResponse
            ->assertRedirect(route('jobs.show', $job->slug).'#apply-form')
            ->assertSessionHasErrors([
                'phone' => 'Веќе имате испратено апликација за овој оглас со овој телефонски број.',
            ]);

        $this->assertDatabaseCount('job_applications', 1);
        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'phone' => '075 295 137',
            'phone_normalized' => '38975295137',
        ]);

        Mail::assertSent(NewJobApplicationNotification::class, 1);
    }

    public function test_same_phone_can_apply_to_different_job_listings(): void
    {
        Mail::fake();
        $this->prepareStageOneSchema();

        [, $firstJob] = $this->createCompanyAndJob('promoter-first-job');
        [, $secondJob] = $this->createCompanyAndJob('promoter-second-job');

        $this->post(route('jobs.apply', $firstJob->slug), [
            'full_name' => 'Марија Кандидат',
            'phone' => '071 111 111',
            'city' => 'Битола',
            'message' => 'Апликација за првиот оглас.',
        ])->assertRedirect(route('jobs.show', $firstJob->slug).'#apply-form');

        $this->post(route('jobs.apply', $secondJob->slug), [
            'full_name' => 'Марија Кандидат',
            'phone' => '+389 71 111 111',
            'city' => 'Битола',
            'message' => 'Апликација за вториот оглас.',
        ])->assertRedirect(route('jobs.show', $secondJob->slug).'#apply-form');

        $this->assertDatabaseCount('job_applications', 2);
        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $firstJob->id,
            'phone_normalized' => '38971111111',
        ]);
        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $secondJob->id,
            'phone_normalized' => '38971111111',
        ]);

        Mail::assertSent(NewJobApplicationNotification::class, 2);
    }

    private function prepareStageOneSchema(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->dropUnique('job_applications_listing_phone_unique');
            $table->string('phone_normalized', 15)->nullable()->change();
        });
    }

    /**
     * @return array{0: Company, 1: JobListing}
     */
    private function createCompanyAndJob(string $slug): array
    {
        $company = Company::create([
            'name' => 'Компанија Тест '.uniqid(),
            'email' => $slug.'@test.mk',
            'phone' => '070123456',
            'description' => 'Тест компанија',
        ]);

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Промотер '.uniqid(),
            'slug' => $slug,
            'description' => 'Опис на оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        return [$company, $job];
    }
}
