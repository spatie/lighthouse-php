<?php

namespace Spatie\Lighthouse\Enums\Concerns;

use BackedEnum;
use Spatie\Lighthouse\Exceptions\InvalidEnumValue;

/** @mixin BackedEnum */
trait EnumMethods
{
    public static function values(): array
    {
        return array_map(fn (BackedEnum $category) => $category->value, self::cases());
    }

    public static function fromString(string $value): self
    {
        $enum = self::tryFrom($value);

        if (! $enum) {
            throw InvalidEnumValue::make($value, self::class);
        }

        return $enum;
    }
}
