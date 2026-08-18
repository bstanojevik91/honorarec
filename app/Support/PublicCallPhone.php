<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class PublicCallPhone
{
    public const NO_PUBLIC_CALL_TOKEN = '__NO_PUBLIC_CALL__';

    public static function normalize(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $candidates = collect(preg_split('/(?:\r\n|\r|\n|,|;|\|)+/', $phone) ?: [])
            ->map(fn (string $candidate): string => trim($candidate))
            ->filter();

        if (self::publishingDisabled($candidates)) {
            return null;
        }

        foreach ($candidates as $candidate) {
            if (str_starts_with($candidate, '+')) {
                $normalized = '+'.preg_replace('/\D+/', '', substr($candidate, 1));
            } else {
                $normalized = preg_replace('/\D+/', '', $candidate);
            }

            if (! is_string($normalized) || $normalized === '') {
                continue;
            }

            if (preg_match('/^(?:\+389\d{8}|0\d{8})$/', $normalized) === 1) {
                return $normalized;
            }
        }

        return null;
    }

    /**
     * @param  Collection<int, string>  $candidates
     */
    private static function publishingDisabled(Collection $candidates): bool
    {
        return $candidates
            ->map(fn (string $candidate): string => mb_strtoupper($candidate))
            ->contains(self::NO_PUBLIC_CALL_TOKEN);
    }
}
