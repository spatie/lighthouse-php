<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;
use Spatie\Lighthouse\Support\Arr;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

it('can get the scores of a real site', function () {
    $scores = Lighthouse::url('https://example.com')->run()->scores();

    // remove "performance" as that key is sometimes empty
    // on GitHub actions due to less powerful hardware
    $expectedCategories = Arr::without(Category::values(), Category::Performance->value);

    expect($scores)->toHaveKeys($expectedCategories);
});

it('will throw an exception when the process times out', function () {
    Lighthouse::url('https://example.com')
        ->timeoutInSeconds(1)
        ->run();
})->throws(ProcessTimedOutException::class);
