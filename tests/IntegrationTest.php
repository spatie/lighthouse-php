<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;
use Spatie\Lighthouse\Support\Arr;
use Spatie\TemporaryDirectory\TemporaryDirectory;
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

it('can get the dev tools log', function () {
    $result = Lighthouse::url('https://example.com')->run();

    $rawHarData = $result->devToolsLog();
    expect($rawHarData)->not->toBeNull()
        ->and($rawHarData)->toBeArray()
        ->and($rawHarData)->not->toBeEmpty();

    $firstEntry = $rawHarData[0];

    expect($firstEntry)->toHaveKey('method')
        ->and($firstEntry)->toHaveKey('params');
});
