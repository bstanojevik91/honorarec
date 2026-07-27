<?php

namespace Tests\Unit;

use App\Support\PhoneNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PhoneNormalizerTest extends TestCase
{
    #[DataProvider('equivalentMacedonianPhones')]
    public function test_it_normalizes_equivalent_macedonian_phone_formats(string $phone): void
    {
        $this->assertSame('38975295137', PhoneNormalizer::normalize($phone));
    }

    public static function equivalentMacedonianPhones(): array
    {
        return [
            ['075 295 137'],
            ['075-295-137'],
            ['075/295/137'],
            ['+389 75 295 137'],
            ['00389 75 295 137'],
        ];
    }

    #[DataProvider('invalidPhones')]
    public function test_it_rejects_invalid_phone_numbers(?string $phone): void
    {
        $this->assertNull(PhoneNormalizer::normalize($phone));
    }

    public static function invalidPhones(): array
    {
        return [
            [null],
            [''],
            ['   '],
            ['abc'],
            ['123'],
            ['+'],
        ];
    }

    public function test_it_preserves_reasonable_international_numbers_as_digits(): void
    {
        $this->assertSame('441234567890', PhoneNormalizer::normalize('+44 1234 567890'));
    }
}
