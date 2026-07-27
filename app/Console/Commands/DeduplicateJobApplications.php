<?php

namespace App\Console\Commands;

use App\Models\JobApplication;
use App\Support\PhoneNormalizer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DeduplicateJobApplications extends Command
{
    protected $signature = 'job-applications:deduplicate {--apply : Persist phone_normalized values and remove duplicate applications}';

    protected $description = 'Analyze and optionally clean duplicate job applications by normalized phone number';

    public function handle(): int
    {
        if (! Schema::hasTable('job_applications')) {
            $this->error('The job_applications table does not exist.');

            return self::FAILURE;
        }

        $analysis = $this->analyzeApplications($this->loadApplications());

        $this->renderAnalysis($analysis);

        if (! $this->option('apply')) {
            $this->newLine();
            $this->info('Dry run complete. No database or file changes were made.');

            return self::SUCCESS;
        }

        if (! Schema::hasColumn('job_applications', 'phone_normalized')) {
            $this->error('Apply mode requires the phone_normalized column. Run php artisan migrate first.');

            return self::FAILURE;
        }

        if ($analysis['invalidApplications']->isNotEmpty()) {
            $this->newLine();
            $this->error('Apply mode aborted because some legacy phone numbers could not be normalized. Review those records manually first.');

            return self::FAILURE;
        }

        try {
            $result = $this->applyCleanup();
        } catch (RuntimeException $exception) {
            $this->newLine();
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Cleanup complete.');
        $this->line("Normalized applications updated: {$result['updatedCount']}");
        $this->line("Duplicate applications removed: {$result['removedCount']}");
        $this->line("CV files reassigned: {$result['reassignedCount']}");
        $this->line("CV files deleted after commit: {$result['deletedFileCount']}");
        $this->line('Log file: '.storage_path('logs/job-application-deduplication.log'));

        return self::SUCCESS;
    }

    private function analyzeApplications(EloquentCollection $applications): array
    {
        $rows = $applications
            ->map(fn (JobApplication $application): array => [
                'application' => $application,
                'normalized_phone' => PhoneNormalizer::normalize($application->phone),
            ])
            ->values();

        $invalidApplications = $rows
            ->filter(fn (array $row): bool => $row['normalized_phone'] === null)
            ->values();

        $duplicateGroups = $rows
            ->filter(fn (array $row): bool => $row['normalized_phone'] !== null)
            ->groupBy(fn (array $row): string => $row['application']->job_listing_id.'|'.$row['normalized_phone'])
            ->filter(fn (Collection $group): bool => $group->count() > 1)
            ->map(fn (Collection $group): array => $this->buildDuplicateGroupSummary($group))
            ->values();

        $applicationsNeedingPhoneUpdateCount = $rows
            ->filter(function (array $row): bool {
                $normalizedPhone = $row['normalized_phone'];

                return $normalizedPhone !== null
                    && $row['application']->phone_normalized !== $normalizedPhone;
            })
            ->count();

        return [
            'total' => $applications->count(),
            'invalidApplications' => $invalidApplications,
            'invalidCount' => $invalidApplications->count(),
            'duplicateGroups' => $duplicateGroups,
            'duplicateGroupCount' => $duplicateGroups->count(),
            'wouldRemoveCount' => $duplicateGroups->sum(fn (array $group): int => count($group['remove_ids'])),
            'applicationsNeedingPhoneUpdateCount' => $applicationsNeedingPhoneUpdateCount,
        ];
    }

    private function buildDuplicateGroupSummary(Collection $group): array
    {
        $ordered = $this->sortRowsForRetention($group)->values();
        $retained = $ordered->first();
        $records = $ordered
            ->map(function (array $row): array {
                /** @var JobApplication $application */
                $application = $row['application'];

                return [
                    'id' => $application->id,
                    'job_listing_id' => $application->job_listing_id,
                    'phone' => $application->phone,
                    'phone_normalized' => $row['normalized_phone'],
                    'has_cv' => filled($application->cv_path),
                    'cv_path' => $application->cv_path,
                    'created_at' => optional($application->created_at)?->toDateTimeString(),
                ];
            })
            ->values()
            ->all();

        return [
            'job_listing_id' => $retained['application']->job_listing_id,
            'phone_normalized' => $retained['normalized_phone'],
            'retained_id' => $retained['application']->id,
            'application_ids' => array_column($records, 'id'),
            'remove_ids' => array_slice(array_column($records, 'id'), 1),
            'records' => $records,
        ];
    }

    private function renderAnalysis(array $analysis): void
    {
        $this->line("Total applications inspected: {$analysis['total']}");
        $this->line("Applications needing phone_normalized update: {$analysis['applicationsNeedingPhoneUpdateCount']}");
        $this->line("Invalid or unnormalizable phone numbers: {$analysis['invalidCount']}");
        $this->line("Duplicate groups found: {$analysis['duplicateGroupCount']}");
        $this->line("Applications that would be removed: {$analysis['wouldRemoveCount']}");

        if ($analysis['invalidApplications']->isNotEmpty()) {
            $this->newLine();
            $this->warn('Invalid phone records requiring manual review:');

            foreach ($analysis['invalidApplications'] as $row) {
                /** @var JobApplication $application */
                $application = $row['application'];

                $this->line(" - Application #{$application->id} on listing #{$application->job_listing_id}: {$application->phone}");
            }
        }

        if ($analysis['duplicateGroups']->isNotEmpty()) {
            $this->newLine();
            $this->warn('Duplicate groups:');

            foreach ($analysis['duplicateGroups'] as $group) {
                $this->line(" - Listing #{$group['job_listing_id']}, normalized phone {$group['phone_normalized']}");
                $this->line('   Application IDs: '.implode(', ', $group['application_ids']));
                $this->line("   Retain application: #{$group['retained_id']}");

                foreach ($group['records'] as $record) {
                    $hasCv = $record['has_cv'] ? 'yes' : 'no';
                    $marker = $record['id'] === $group['retained_id'] ? 'retain' : 'remove';

                    $this->line("   - #{$record['id']} | has_cv: {$hasCv} | created_at: {$record['created_at']} | {$marker}");
                }
            }
        }
    }

    private function applyCleanup(): array
    {
        $logger = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/job-application-deduplication.log'),
        ]);

        $pathsQueuedForDeletion = [];
        $updatedCount = 0;
        $removedCount = 0;
        $reassignedCount = 0;

        DB::transaction(function () use (&$pathsQueuedForDeletion, &$updatedCount, &$removedCount, &$reassignedCount, $logger): void {
            $applications = $this->loadApplications();
            $analysis = $this->analyzeApplications($applications);

            if ($analysis['invalidApplications']->isNotEmpty()) {
                throw new RuntimeException('Cleanup aborted because invalid phone numbers still require manual review.');
            }

            foreach ($applications as $application) {
                $normalizedPhone = PhoneNormalizer::normalize($application->phone);

                if ($normalizedPhone === null) {
                    throw new RuntimeException("Cleanup aborted because application #{$application->id} has an invalid phone number.");
                }

                if ($application->phone_normalized !== $normalizedPhone) {
                    $application->forceFill([
                        'phone_normalized' => $normalizedPhone,
                    ])->save();

                    $updatedCount++;
                }
            }

            $freshApplications = $this->loadApplications();
            $duplicateGroups = $this->analyzeApplications($freshApplications)['duplicateGroups'];

            foreach ($duplicateGroups as $group) {
                $groupApplications = $freshApplications
                    ->whereIn('id', $group['application_ids'])
                    ->values();

                $orderedApplications = $this->sortApplicationsForRetention($groupApplications)->values();
                /** @var JobApplication $retainedApplication */
                $retainedApplication = $orderedApplications->first();
                $duplicateApplications = $orderedApplications->slice(1)->values();
                $replacementCvPath = $this->determineRetainedCvPath($retainedApplication, $duplicateApplications);

                if ($replacementCvPath !== $retainedApplication->cv_path) {
                    $retainedApplication->forceFill([
                        'cv_path' => $replacementCvPath,
                    ])->save();

                    $reassignedCount++;
                }

                foreach ($duplicateApplications as $duplicateApplication) {
                    if ($this->shouldQueueCvDeletion($duplicateApplication->cv_path, $replacementCvPath)) {
                        $pathsQueuedForDeletion[] = $duplicateApplication->cv_path;
                    }
                }

                $duplicateIds = $duplicateApplications->pluck('id')->all();

                if ($duplicateIds !== []) {
                    JobApplication::query()->whereKey($duplicateIds)->delete();
                    $removedCount += count($duplicateIds);
                }

                $logger->info('Deduplicated job application group.', [
                    'job_listing_id' => $group['job_listing_id'],
                    'phone_normalized' => $group['phone_normalized'],
                    'retained_application_id' => $retainedApplication->id,
                    'removed_application_ids' => $duplicateIds,
                    'retained_cv_path' => $retainedApplication->fresh()->cv_path,
                ]);
            }
        });

        $deletedFileCount = 0;

        foreach (collect($pathsQueuedForDeletion)->filter()->unique()->values() as $path) {
            if (! $this->pathBelongsToCvStorage($path)) {
                $this->warn("Skipped unsafe CV cleanup path: {$path}");

                continue;
            }

            if (JobApplication::query()->where('cv_path', $path)->exists()) {
                $this->line("Kept CV file because it is still referenced: {$path}");

                continue;
            }

            Storage::disk('public')->delete($path);
            $deletedFileCount++;
            $this->line("Deleted orphaned CV file: {$path}");
        }

        return [
            'updatedCount' => $updatedCount,
            'removedCount' => $removedCount,
            'reassignedCount' => $reassignedCount,
            'deletedFileCount' => $deletedFileCount,
        ];
    }

    private function determineRetainedCvPath(JobApplication $retainedApplication, Collection $duplicateApplications): ?string
    {
        if ($this->isReusableCvPath($retainedApplication->cv_path)) {
            return $retainedApplication->cv_path;
        }

        $replacement = $duplicateApplications->first(
            fn (JobApplication $application): bool => $this->isReusableCvPath($application->cv_path)
        );

        return $replacement?->cv_path ?? $retainedApplication->cv_path;
    }

    private function shouldQueueCvDeletion(?string $cvPath, ?string $retainedCvPath): bool
    {
        if (! is_string($cvPath) || $cvPath === '') {
            return false;
        }

        if (! $this->pathBelongsToCvStorage($cvPath)) {
            return false;
        }

        return $cvPath !== $retainedCvPath;
    }

    private function isReusableCvPath(?string $cvPath): bool
    {
        return is_string($cvPath)
            && $cvPath !== ''
            && $this->pathBelongsToCvStorage($cvPath)
            && Storage::disk('public')->exists($cvPath);
    }

    private function pathBelongsToCvStorage(?string $path): bool
    {
        if (! is_string($path) || $path === '') {
            return false;
        }

        $normalizedPath = str_replace('\\', '/', trim($path));

        return ! str_contains($normalizedPath, '..')
            && ! Str::startsWith($normalizedPath, ['/'])
            && Str::startsWith($normalizedPath, 'applications/cv/');
    }

    private function loadApplications(): EloquentCollection
    {
        return JobApplication::query()
            ->orderBy('job_listing_id')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    private function sortRowsForRetention(Collection $rows): Collection
    {
        return $rows->sort(function (array $left, array $right): int {
            return $this->compareApplications($left['application'], $right['application']);
        });
    }

    private function sortApplicationsForRetention(Collection $applications): Collection
    {
        return $applications->sort(fn (JobApplication $left, JobApplication $right): int => $this->compareApplications($left, $right));
    }

    private function compareApplications(JobApplication $left, JobApplication $right): int
    {
        $leftTimestamp = $left->created_at?->getTimestamp();
        $rightTimestamp = $right->created_at?->getTimestamp();

        if ($leftTimestamp !== null && $rightTimestamp !== null && $leftTimestamp !== $rightTimestamp) {
            return $leftTimestamp <=> $rightTimestamp;
        }

        return $left->id <=> $right->id;
    }
}
