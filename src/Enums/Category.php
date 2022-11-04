<?php

namespace Spatie\Lighthouse\Enums;

use Spatie\Lighthouse\Exceptions\InvalidCategory;

enum Category: string
{
    case Performance = 'performance';
    case Accessibility = 'accessibility';
    case BestPractices = 'best-practices';
    case Seo = 'seo';
    case ProgressiveWebApp = 'pwa';

    public static function values(): array
    {
        return array_map(function (Category $category) {
            return $category->value;
        }, Category::cases());
    }

    public static function fromString(string $value): self
    {
        $enum = self::tryFrom($value);

        if (! $enum) {
            throw InvalidCategory::make($value);
        }

        return $enum;
    }
}
