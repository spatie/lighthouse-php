<?php

namespace Spatie\Lighthouse\Enums;

use Spatie\Lighthouse\Enums\Concerns\EnumMethods;

enum Category: string
{
    case Performance = 'performance';
    case Accessibility = 'accessibility';
    case BestPractices = 'best-practices';
    case Seo = 'seo';
    case ProgressiveWebApp = 'pwa';

    use EnumMethods;
}
