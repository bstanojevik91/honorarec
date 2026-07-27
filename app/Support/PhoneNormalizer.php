<?php

namespace App\Support;

final class PhoneNormalizer
{
    private const MIN_LENGTH = 8;

    private const MAX_LENGTH = 15;

    public static function normalize(?string $phone): ?string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (! is_string($digits) || $digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00389')) {
            $digits = '389'.substr($digits, 5);
        } elseif (str_starts_with($digits, '0') && self::looksLikeMacedonianLocalNumber($digits)) {
            $digits = '389'.substr($digits, 1);
        }

        if (! preg_match('/^\d+$/', $digits)) {
            return null;
        }

        $length = strlen($digits);

        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            return null;
        }

        return $digits;
    }

    public static function isValidNormalized(?string $phone): bool
    {
        if (! is_string($phone) || $phone === '') {
            return false;
        }

        return self::normalize($phone) === $phone;
    }

    private static function looksLikeMacedonianLocalNumber(string $digits): bool
    {
        $length = strlen($digits);

        return $length >= self::MIN_LENGTH && $length <= 10;
    }
}
