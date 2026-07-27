<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeduplicateJobApplicationsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_dry_run_reports_duplicates_without_changing_data(): void
    {
        Storage::fake('public');
        $this->prepareLegacyJobApplicationsTable();
        [$job] = $this->createLegacyDuplicateSet();

        $this->artisan('job-applications:deduplicate')
            ->expectsOutputToContain('Total applications inspected: 3')
            ->expectsOutputToContain('Duplicate groups found: 1')
            ->expectsOutputToContain("Listing #{$job->id}, normalized phone 38975295137")
            ->expectsOutputToContain('Dry run complete. No database or file changes were made.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('job_applications', 3);
        $this->assertDatabaseHas('job_applications', [
            'phone_normalized' => null,
        ]);
        Storage::disk('public')->assertExists('applications/cv/duplicate-keep.pdf');
        Storage::disk('public')->assertExists('applications/cv/duplicate-delete.pdf');
    }

    public function test_apply_mode_normalizes_records_reassigns_cv_and_removes_duplicates(): void
    {
        Storage::fake('public');
        $this->prepareLegacyJobApplicationsTable();
        [$job, $retainedId, $removedIds] = $this->createLegacyDuplicateSet();

        $this->artisan('job-applications:deduplicate', ['--apply' => true])
            ->expectsOutputToContain('Cleanup complete.')
            ->expectsOutputToContain('Duplicate applications removed: 2')
            ->assertExitCode(0);

        $this->assertDatabaseCount('job_applications', 1);
        $this->assertDatabaseHas('job_applications', [
            'id' => $retainedId,
            'job_listing_id' => $job->id,
            'phone_normalized' => '38975295137',
            'cv_path' => 'applications/cv/duplicate-keep.pdf',
        ]);

        foreach ($removedIds as $removedId) {
            $this->assertDatabaseMissing('job_applications', [
                'id' => $removedId,
            ]);
        }

        Storage::disk('public')->assertExists('applications/cv/duplicate-keep.pdf');
        Storage::disk('public')->assertMissing('applications/cv/duplicate-delete.pdf');
    }

    public function test_apply_mode_is_safe_to_repeat_after_cleanup_has_already_run(): void
    {
        Storage::fake('public');
        $this->prepareLegacyJobApplicationsTable();
        [$job, $retainedId] = $this->createLegacyDuplicateSet();

        $this->artisan('job-applications:deduplicate', ['--apply' => true])
            ->assertExitCode(0);

        $this->artisan('job-applications:deduplicate', ['--apply' => true])
            ->expectsOutputToContain('Cleanup complete.')
            ->expectsOutputToContain('Normalized applications updated: 0')
            ->expectsOutputToContain('Duplicate applications removed: 0')
            ->assertExitCode(0);

        $this->assertDatabaseCount('job_applications', 1);
        $this->assertDatabaseHas('job_applications', [
            'id' => $retainedId,
            'job_listing_id' => $job->id,
            'phone_normalized' => '38975295137',
            'cv_path' => 'applications/cv/duplicate-keep.pdf',
        ]);

        Storage::disk('public')->assertExists('applications/cv/duplicate-keep.pdf');
        Storage::disk('public')->assertMissing('applications/cv/duplicate-delete.pdf');
    }

    private function prepareLegacyJobApplicationsTable(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->dropUnique('job_applications_listing_phone_unique');
            $table->string('phone_normalized', 15)->nullable()->change();
        });
    }

    /**
     * @return array{0: JobListing, 1: int, 2: array<int, int>}
     */
    private function createLegacyDuplicateSet(): array
    {
        $company = Company::create([
            'name' => 'Компанија Команда',
            'email' => 'command@test.mk',
            'phone' => '070123456',
            'description' => 'Тест компанија',
        ]);

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Асистент',
            'slug' => 'legacy-command-job',
            'description' => 'Опис на оглас.',
            'daily_pay' => 1200,
            'location' => 'Скопје',
            'category' => 'Администрација',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        Storage::disk('public')->put('applications/cv/duplicate-keep.pdf', 'keep');
        Storage::disk('public')->put('applications/cv/duplicate-delete.pdf', 'delete');

        $timestamp = now()->subDay();

        DB::table('job_applications')->insert([
            [
                'id' => 1,
                'job_listing_id' => $job->id,
                'full_name' => 'Кандидат Еден',
                'phone' => '075 295 137',
                'phone_normalized' => null,
                'city' => 'Скопје',
                'message' => 'Прва апликација.',
                'cv_path' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 2,
                'job_listing_id' => $job->id,
                'full_name' => 'Кандидат Два',
                'phone' => '+389 75 295 137',
                'phone_normalized' => null,
                'city' => 'Скопје',
                'message' => 'Втора апликација.',
                'cv_path' => 'applications/cv/duplicate-keep.pdf',
                'created_at' => $timestamp->copy()->addMinute(),
                'updated_at' => $timestamp->copy()->addMinute(),
            ],
            [
                'id' => 3,
                'job_listing_id' => $job->id,
                'full_name' => 'Кандидат Три',
                'phone' => '00389 75 295 137',
                'phone_normalized' => null,
                'city' => 'Скопје',
                'message' => 'Трета апликација.',
                'cv_path' => 'applications/cv/duplicate-delete.pdf',
                'created_at' => $timestamp->copy()->addMinutes(2),
                'updated_at' => $timestamp->copy()->addMinutes(2),
            ],
        ]);

        return [$job, 1, [2, 3]];
    }
}
