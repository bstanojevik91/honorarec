<?php

namespace App\Support;

final class CategoryName
{
    public static function clean(string $name): string
    {
        return trim(preg_replace('/[\s\p{Z}]+/u', ' ', $name));
    }

    public static function normalize(string $name): string
    {
        return mb_strtolower(self::clean($name), 'UTF-8');
    }

    public static function display(string $name): string
    {
        $name = self::clean($name);

        if (self::normalize($name) === 'угостителство') {
            return 'Угостителство';
        }

        return mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8').mb_substr($name, 1, null, 'UTF-8');
    }
}
