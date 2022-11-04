<?php

namespace Spatie\Lighthouse\Exceptions;

use BackedEnum;
use Exception;

class InvalidEnumValue extends Exception
{
    /**
     * @param  string  $invalidValue
     * @param  class-string<BackedEnum>  $enumClass
     * @return static
     */
    public static function make(string $invalidValue, string $enumClass): self
    {
        $validValues = implode(', ', $enumClass::values());

        return new self("The given value `{$invalidValue}` is not valid for {$enumClass}. Valid values are {$validValues}");
    }
}
