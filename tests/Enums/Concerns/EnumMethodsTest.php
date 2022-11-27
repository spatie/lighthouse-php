<?php

use Spatie\Lighthouse\Enums\Category;

it('can determine the label', function () {
    expect(Category::BestPractices->label())->toBe('best practices');
});
