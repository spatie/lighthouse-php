<?php

namespace Spatie\Lighthouse\Exceptions;

class CouldNotRunLighthouse extends \Exception
{
    public static function make(string $errorOutput): self
    {
        return new self("Could not run Lighthouse. Error : `{$errorOutput}`");
    }
}
