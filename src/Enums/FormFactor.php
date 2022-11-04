<?php

namespace Spatie\Lighthouse\Enums;

use Spatie\Lighthouse\Enums\Concerns\EnumMethods;

enum FormFactor: string
{
    case Mobile = 'mobile';
    case Desktop = 'desktop';

    use EnumMethods;
}
