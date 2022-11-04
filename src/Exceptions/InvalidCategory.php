<?php

namespace Spatie\Lighthouse\Exceptions;

use Exception;
use Spatie\Lighthouse\Enums\Category;

class InvalidCategory extends Exception
{
    public static function make(string $invalidCategory): self
    {
        $validCategories = implode(', ', Category::values());

        return new self("The given category `{$invalidCategory}` is not valid. Valid categories are {$validCategories}");
    }
}
