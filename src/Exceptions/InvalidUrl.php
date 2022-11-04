<?php

namespace Spatie\Lighthouse\Exceptions;

use Exception;
use Spatie\Lighthouse\Enums\Category;

class InvalidUrl extends Exception
{
    public static function make(string $invalidUrl): self
    {
        return new self("The given URL `{$invalidUrl}` is not valid.");
    }
}
