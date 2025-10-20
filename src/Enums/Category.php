<?php

namespace Spatie\Lighthouse\Enums;

use Spatie\Lighthouse\Enums\Concerns\EnumMethods;

enum Category: string
{
    use EnumMethods;

    case Performance = 'performance';
    case Accessibility = 'accessibility';
    case BestPractices = 'best-practices';
    case Seo = 'seo';
}
