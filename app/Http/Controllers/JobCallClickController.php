<?php

namespace App\Http\Controllers;

use App\Models\JobCallClick;
use App\Models\JobListing;
use App\Support\PublicCallPhone;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class JobCallClickController extends Controller
{
    private const DUPLICATE_WINDOW_SECONDS = 10;

    private const VISITOR_TOKEN_MAX_LENGTH = 120;

    public function store(Request $request, string $slug)
    {
        if (! Schema::hasTable('job_call_clicks')) {
            return response()->noContent();
        }

        try {
            $jobListing = JobListing::query()
                ->with('company')
                ->where('status', JobListing::STATUS_ACTIVE)
                ->where('slug', $slug)
                ->first();

            if ($jobListing === null || PublicCallPhone::normalize($jobListing->company?->phone) === null) {
                return response()->noContent();
            }

            $visitorHash = $this->resolveVisitorHash($request);

            if ($visitorHash === null) {
                return response()->noContent();
            }

            if ($this->recentDuplicateExists($jobListing->id, $visitorHash)) {
                return response()->noContent();
            }

            JobCallClick::query()->create([
                'job_listing_id' => $jobListing->id,
                'visitor_hash' => $visitorHash,
                'time_bucket' => $this->currentTimeBucket(),
                'dedupe_key' => $this->dedupeKey($jobListing->id, $visitorHash),
            ]);
        } catch (QueryException $exception) {
            if (! $this->isDuplicateKeyViolation($exception)) {
                Log::warning('Failed to track job call click.', [
                    'job_slug' => $slug,
                    'error' => $exception->getMessage(),
                ]);
            }
        } catch (Throwable $throwable) {
            Log::warning('Failed to track job call click.', [
                'job_slug' => $slug,
                'error' => $throwable->getMessage(),
            ]);
        }

        return response()->noContent();
    }

    private function resolveVisitorHash(Request $request): ?string
    {
        $visitorToken = trim((string) $request->input('visitor_token', ''));

        if ($visitorToken !== '') {
            return hash_hmac(
                'sha256',
                substr($visitorToken, 0, self::VISITOR_TOKEN_MAX_LENGTH),
                $this->trackingSecret()
            );
        }

        if (! $request->hasSession()) {
            return null;
        }

        $sessionId = $request->session()->getId();

        if (! is_string($sessionId) || $sessionId === '') {
            return null;
        }

        return hash_hmac('sha256', $sessionId, $this->trackingSecret());
    }

    private function recentDuplicateExists(int $jobListingId, string $visitorHash): bool
    {
        return JobCallClick::query()
            ->where('job_listing_id', $jobListingId)
            ->where('visitor_hash', $visitorHash)
            ->where('created_at', '>=', now()->subSeconds(self::DUPLICATE_WINDOW_SECONDS))
            ->exists();
    }

    private function currentTimeBucket(): int
    {
        return intdiv(now()->timestamp, self::DUPLICATE_WINDOW_SECONDS);
    }

    private function dedupeKey(int $jobListingId, string $visitorHash): string
    {
        return hash_hmac(
            'sha256',
            implode('|', [$jobListingId, $visitorHash, $this->currentTimeBucket()]),
            $this->trackingSecret()
        );
    }

    private function trackingSecret(): string
    {
        $appKey = (string) config('app.key', '');

        if (str_starts_with($appKey, 'base64:')) {
            $decoded = base64_decode(substr($appKey, 7), true);

            if (is_string($decoded) && $decoded !== '') {
                return $decoded;
            }
        }

        return $appKey !== '' ? $appKey : 'job-call-click-tracking';
    }

    private function isDuplicateKeyViolation(QueryException $exception): bool
    {
        $sqlState = (string) $exception->getCode();
        $message = $exception->getMessage();

        return in_array($sqlState, ['23000', '23505'], true)
            || str_contains($message, 'job_call_clicks_dedupe_key_unique');
    }
}
