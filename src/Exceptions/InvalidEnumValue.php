<?php

namespace Spatie\Lighthouse\Exceptions;

use Exception;

class InvalidEnumValue extends Exception
{
    /**
     * @phpstan-param class-string<\Spatie\Lighthouse\Enums\Category|\Spatie\Lighthouse\Enums\FormFactor> $enumClass
     */
    public static function make(string $invalidValue, string $enumClass): self
    {
        $validValues = implode(', ', $enumClass::values());

        return new self("The given value `{$invalidValue}` is not valid for {$enumClass}. Valid values are {$validValues}");
    }
}
