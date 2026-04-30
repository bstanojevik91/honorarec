<?php

namespace App\Support;

class MacedonianPhone
{
    public const VALIDATION_REGEX = '/^(?:\+389\d{8}|0\d{8})$/';

    public static function sanitize(null|string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, '+')) {
            $digits = preg_replace('/\D+/', '', substr($value, 1));

            return $digits !== '' ? '+'.$digits : null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits !== '' ? $digits : null;
    }
}
