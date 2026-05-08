<?php

namespace App\Support;

class LocationOptions
{
    /**
     * @return array<int, array{name:string, municipalities:array<int, array{name:string}>}>
     */
    public static function tree(): array
    {
        return collect(config('locations.cities', []))
            ->map(function (mixed $entry): array {
                if (is_string($entry)) {
                    return [
                        'name' => trim($entry),
                        'municipalities' => [],
                    ];
                }

                $name = trim((string) ($entry['name'] ?? ''));
                $municipalities = collect($entry['municipalities'] ?? [])
                    ->map(function (mixed $municipality): array {
                        if (is_string($municipality)) {
                            return ['name' => trim($municipality)];
                        }

                        return ['name' => trim((string) ($municipality['name'] ?? ''))];
                    })
                    ->filter(fn (array $municipality): bool => $municipality['name'] !== '')
                    ->values()
                    ->all();

                return [
                    'name' => $name,
                    'municipalities' => $municipalities,
                ];
            })
            ->filter(fn (array $city): bool => $city['name'] !== '')
            ->values()
            ->all();
    }

    public static function displayLabel(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $match = self::lookup()[self::normalizeValue($value)] ?? null;

        return $match['name'] ?? $value;
    }

    public static function matches(?string $jobLocation, ?string $selectedLocation): bool
    {
        $selectedLocation = trim((string) $selectedLocation);

        if ($selectedLocation === '') {
            return true;
        }

        $jobLocation = trim((string) $jobLocation);

        if ($jobLocation === '') {
            return false;
        }

        $match = self::lookup()[self::normalizeValue($selectedLocation)] ?? null;

        if ($match === null) {
            return str_contains(self::normalizeText($jobLocation), self::normalizeText($selectedLocation));
        }

        foreach ($match['search_terms'] as $term) {
            if (self::containsLocationTerm($jobLocation, $term)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, array{name:string, type:string, parent:?string, search_terms:array<int, string>}>
     */
    private static function lookup(): array
    {
        static $lookup;

        if (is_array($lookup)) {
            return $lookup;
        }

        $lookup = [];

        foreach (self::tree() as $city) {
            $municipalityNames = collect($city['municipalities'])
                ->pluck('name')
                ->values()
                ->all();

            $lookup[self::normalizeValue($city['name'])] = [
                'name' => $city['name'],
                'type' => 'city',
                'parent' => null,
                'search_terms' => array_values(array_unique([
                    $city['name'],
                    ...$municipalityNames,
                ])),
            ];

            foreach ($municipalityNames as $municipalityName) {
                $lookup[self::normalizeValue($municipalityName)] = [
                    'name' => $municipalityName,
                    'type' => 'municipality',
                    'parent' => $city['name'],
                    'search_terms' => [$municipalityName],
                ];
            }
        }

        return $lookup;
    }

    private static function containsLocationTerm(string $haystack, string $needle): bool
    {
        $normalizedHaystack = ' ' . self::normalizeText($haystack) . ' ';
        $normalizedNeedle = ' ' . self::normalizeText($needle) . ' ';

        return str_contains($normalizedHaystack, $normalizedNeedle);
    }

    private static function normalizeValue(string $value): string
    {
        return self::normalizeText($value);
    }

    private static function normalizeText(string $value): string
    {
        $normalized = preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower(trim($value)));

        return trim((string) $normalized);
    }
}
