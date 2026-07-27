<?php

namespace Tests\Feature;

use App\Mail\NewJobApplicationNotification;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class JobApplicationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_receives_email_when_a_candidate_applies(): void
    {
        Storage::fake('public');
        Mail::fake();

        $company = Company::create([
            'name' => 'Компанија Тест',
            'email' => 'company@test.mk',
            'phone' => '070123456',
            'description' => 'Тест компанија',
        ]);

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Промотер',
            'slug' => 'promoter-test',
            'description' => 'Опис на оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $response = $this->post(route('jobs.apply', $job->slug), [
            'full_name' => 'Петар Апликант',
            'phone' => '071111111',
            'city' => 'Битола',
            'message' => 'Заинтересиран сум за огласот.',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
        ]);

        $response->assertRedirect(route('jobs.show', $job->slug).'#apply-form');
        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'full_name' => 'Петар Апликант',
            'phone' => '071111111',
            'phone_normalized' => '38971111111',
            'city' => 'Битола',
        ]);

        Mail::assertSent(NewJobApplicationNotification::class, function (NewJobApplicationNotification $mail) use ($company, $job): bool {
            return $mail->hasTo($company->email)
                && $mail->company->is($company)
                && $mail->jobListing->is($job)
                && $mail->application->full_name === 'Петар Апликант'
                && $mail->cvUrl !== null;
        });
    }

    public function test_application_is_still_saved_when_email_sending_fails(): void
    {
        Log::spy();

        $company = Company::create([
            'name' => 'Компанија Без Испорака',
            'email' => 'failing-company@test.mk',
            'phone' => '070123456',
            'description' => 'Тест компанија',
        ]);

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Асистент',
            'slug' => 'asistent-test',
            'description' => 'Опис на оглас.',
            'daily_pay' => 1400,
            'location' => 'Охрид',
            'category' => 'Администрација',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        Mail::shouldReceive('to')->once()->with($company->email)->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new RuntimeException('SMTP failure'));

        $response = $this->post(route('jobs.apply', $job->slug), [
            'full_name' => 'Елена Кандидат',
            'phone' => '072222222',
            'city' => 'Прилеп',
            'message' => 'Ме интересира работата.',
        ]);

        $response->assertRedirect(route('jobs.show', $job->slug).'#apply-form');
        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'full_name' => 'Елена Кандидат',
            'phone' => '072222222',
            'phone_normalized' => '38972222222',
            'city' => 'Прилеп',
        ]);

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function (string $message, array $context) use ($company, $job): bool {
                return $message === 'Failed to send new job application email.'
                    && $context['job_listing_id'] === $job->id
                    && $context['company_id'] === $company->id
                    && $context['company_email'] === $company->email
                    && $context['error'] === 'SMTP failure';
            });
    }
}
