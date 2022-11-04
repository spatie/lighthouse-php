<?php

namespace Spatie\Lighthouse\Exceptions;

use Exception;

class AuditDoesNotExist extends Exception
{
    public static function make(string $auditName): self
    {
        return new self("The result does not contain an audit named `{$auditName}`. Call `auditNames()` on a result to see valid audit names.");
    }
}
