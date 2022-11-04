<?php

namespace Spatie\Lighthouse\Exceptions;

use Exception;

class LighthouseReportedError extends Exception
{
    public static function make(string $message, string $code)
    {
        return new self("There was an error running Lighthouse: `{$message}` (code: {$code})");
    }
}
