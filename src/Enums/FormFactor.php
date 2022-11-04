<?php

namespace Spatie\Lighthouse\Enums;

use Spatie\Lighthouse\Enums\Concerns\EnumMethods;

enum FormFactor: string
{
    use EnumMethods;
    case Mobile = 'mobile';
    case Desktop = 'desktop';
}
