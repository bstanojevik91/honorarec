<?php

namespace App\Support;

final class PublicUrl
{
    public static function baseUrl(): string
    {
        $configuredUrl = (string) config('app.url', 'http://localhost');

        return rtrim(self::normalize($configuredUrl), '/');
    }

    public static function absolutePath(string $path): string
    {
        $normalizedPath = '/' . ltrim($path, '/');

        if ($normalizedPath === '//') {
            $normalizedPath = '/';
        }

        return self::baseUrl() . $normalizedPath;
    }

    public static function normalize(string $url): string
    {
        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['host'])) {
            return $url;
        }

        if (self::isLocalHost($parts['host'])) {
            return $url;
        }

        $normalized = 'https://' . $parts['host'];

        if (isset($parts['port']) && ! in_array((int) $parts['port'], [80, 443], true)) {
            $normalized .= ':' . $parts['port'];
        }

        $normalized .= $parts['path'] ?? '';
        $normalized .= isset($parts['query']) ? '?' . $parts['query'] : '';
        $normalized .= isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return $normalized;
    }

    public static function shouldForceHttps(): bool
    {
        $host = parse_url((string) config('app.url', ''), PHP_URL_HOST);

        return is_string($host) && $host !== '' && ! self::isLocalHost($host);
    }

    private static function isLocalHost(string $host): bool
    {
        return in_array($host, ['localhost', '127.0.0.1'], true)
            || str_ends_with($host, '.localhost')
            || str_ends_with($host, '.test');
    }
}
