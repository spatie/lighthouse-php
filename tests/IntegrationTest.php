<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Exceptions\LighthouseReportedError;
use Spatie\Lighthouse\Lighthouse;
use Spatie\Lighthouse\Support\Arr;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

it('can get the scores of a real site', function () {
    $scores = Lighthouse::url('https://example.com')->run()->scores();

    // remove "performance" as that key is sometimes empty
    // on GitHub actions due to less powerful hardware
    $expectedCategories = Arr::without(Category::values(), Category::Performance->value);
    $expectedCategories = Arr::without($expectedCategories, Category::ProgressiveWebApp->value);

    expect($scores)->toHaveKeys($expectedCategories);
});

it('will throw an exception when the process times out', function () {
    Lighthouse::url('https://example.com')
        ->timeoutInSeconds(1)
        ->run();
})->throws(ProcessTimedOutException::class);

it('can set maxWaitForLoad and complete within expected time', function () {
    $startTime = microtime(true);

    $result = Lighthouse::url('https://example.com')
        ->maxWaitForLoad(5000) // 1 second max wait for load
        ->timeoutInSeconds(60) // Overall timeout higher than maxWaitForLoad
        ->run();

    $endTime = microtime(true);
    $executionTime = $endTime - $startTime;

    expect($result->scores())->toBeArray();
    expect($executionTime)->toBeLessThan(15);
});

it('throws an exception when lighthouse reports a runtime error', function () {
    // Use an invalid URL that will trigger a runtime error
    Lighthouse::url('https://this-domain-absolutely-does-not-exist-12345.com')
        ->maxWaitForLoad(1000)
        ->timeoutInSeconds(10)
        ->run();
})->throws(LighthouseReportedError::class);
