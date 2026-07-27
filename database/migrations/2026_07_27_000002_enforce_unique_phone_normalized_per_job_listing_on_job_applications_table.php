<?php

use App\Support\PhoneNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_applications', 'phone_normalized')) {
            throw new RuntimeException('Cannot enforce unique job application phone numbers because the phone_normalized column is missing.');
        }

        $applicationsMissingNormalizedPhone = DB::table('job_applications')
            ->select('id', 'phone', 'phone_normalized')
            ->whereNull('phone_normalized')
            ->orWhere('phone_normalized', '')
            ->orderBy('id')
            ->get();

        $invalidLegacyPhones = $applicationsMissingNormalizedPhone
            ->map(function (object $application): array {
                return [
                    'id' => $application->id,
                    'normalized_phone' => PhoneNormalizer::normalize($application->phone),
                ];
            })
            ->filter(fn (array $application): bool => $application['normalized_phone'] === null)
            ->take(10)
            ->values();

        if ($invalidLegacyPhones->isNotEmpty()) {
            $ids = $invalidLegacyPhones->pluck('id')->implode(', ');

            throw new RuntimeException(
                "Cannot add the job application phone uniqueness constraint because some legacy phone numbers could not be normalized for application IDs: {$ids}. ".
                'Run php artisan job-applications:deduplicate --apply and manually review any invalid phone numbers before retrying this migration.'
            );
        }

        foreach ($applicationsMissingNormalizedPhone as $application) {
            $normalizedPhone = PhoneNormalizer::normalize($application->phone);

            if ($normalizedPhone === null) {
                continue;
            }

            DB::table('job_applications')
                ->where('id', $application->id)
                ->update([
                    'phone_normalized' => $normalizedPhone,
                ]);
        }

        $remainingNullIds = DB::table('job_applications')
            ->whereNull('phone_normalized')
            ->orWhere('phone_normalized', '')
            ->orderBy('id')
            ->limit(10)
            ->pluck('id')
            ->all();

        if ($remainingNullIds !== []) {
            $ids = implode(', ', $remainingNullIds);

            throw new RuntimeException(
                "Cannot add the job application phone uniqueness constraint because phone_normalized is still null or empty for application IDs: {$ids}. ".
                'Run php artisan job-applications:deduplicate --apply before retrying this migration.'
            );
        }

        $invalidNormalized = DB::table('job_applications')
            ->select('id', 'phone_normalized')
            ->orderBy('id')
            ->get()
            ->filter(fn (object $application): bool => ! PhoneNormalizer::isValidNormalized($application->phone_normalized))
            ->take(10)
            ->values();

        if ($invalidNormalized->isNotEmpty()) {
            $samples = $invalidNormalized
                ->map(fn (object $application): string => "#{$application->id} ({$application->phone_normalized})")
                ->implode(', ');

            throw new RuntimeException(
                "Cannot add the job application phone uniqueness constraint because invalid normalized phone values remain: {$samples}. ".
                'Run php artisan job-applications:deduplicate --apply and correct any remaining legacy records before retrying this migration.'
            );
        }

        $duplicateGroups = DB::table('job_applications')
            ->select('job_listing_id', 'phone_normalized', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('job_listing_id', 'phone_normalized')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicateGroups->isNotEmpty()) {
            $samples = $duplicateGroups
                ->take(3)
                ->map(function (object $group): string {
                    $ids = DB::table('job_applications')
                        ->where('job_listing_id', $group->job_listing_id)
                        ->where('phone_normalized', $group->phone_normalized)
                        ->orderBy('id')
                        ->pluck('id')
                        ->implode(', ');

                    return "listing {$group->job_listing_id}, phone {$group->phone_normalized}, ids [{$ids}]";
                })
                ->implode('; ');

            throw new RuntimeException(
                "Cannot add the job application phone uniqueness constraint because duplicate groups still exist: {$samples}. ".
                'Run php artisan job-applications:deduplicate --apply before retrying this migration.'
            );
        }

        Schema::table('job_applications', function (Blueprint $table): void {
            $table->string('phone_normalized', 15)->nullable(false)->change();
            $table->unique(
                ['job_listing_id', 'phone_normalized'],
                'job_applications_listing_phone_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->dropUnique('job_applications_listing_phone_unique');
            $table->string('phone_normalized', 15)->nullable()->change();
        });
    }
};
