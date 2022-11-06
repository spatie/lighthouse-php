<?php

use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Exceptions\AuditDoesNotExist;
use Spatie\Lighthouse\LighthouseResult;
use function Spatie\Snapshots\assertMatchesSnapshot;
use Spatie\TemporaryDirectory\TemporaryDirectory;

beforeEach(function () {
    $rawResult = getJsonStub('example-com-result');

    $this->lighthouseResult = new LighthouseResult($rawResult);
});

it('can get the scores', function () {
    expect($this->lighthouseResult->scores())->toEqual([
        'performance' => 100,
        'accessibility' => 92,
        'best-practices' => 100,
        'seo' => 91,
        'pwa' => 30,
    ]);
});

it('can get the results', function () {
    assertMatchesSnapshot($this->lighthouseResult->get());
});

it('can get a certain property of the json results', function () {
    expect($this->lighthouseResult->get('categories.accessibility.score'))->toEqual(0.92);
});

it('can get the html', function () {
    assertMatchesSnapshot($this->lighthouseResult->html());
});

it('can save the html', function () {
    $tempDirectory = (new TemporaryDirectory(getTemporaryDirectoryPath()))->empty();

    $reportPath = $tempDirectory->path('report.html');

    $this->lighthouseResult->saveHtml($reportPath);

    expect(file_get_contents($reportPath))->toEqual($this->lighthouseResult->html());
});

it('can return the raw results', function () {
    expect($this->lighthouseResult->rawResults())->toEqual(getJsonStub('example-com-result'));
});

it('can get a specific key of the raw results', function () {
    expect($this->lighthouseResult->rawResults('lhr.lighthouseVersion'))->toEqual('9.6.8');
});

it('can get the configSettings', function () {
    assertMatchesSnapshot($this->lighthouseResult->configSettings());

    expect($this->lighthouseResult->configSettings('screenEmulation.mobile'))->toBeTrue();
});

it('can get the emulated form factor', function () {
    expect($this->lighthouseResult->formFactor())->toEqual(FormFactor::Desktop);
});

it('can determine if network throttling was enabled', function () {
    expect($this->lighthouseResult->networkThrottlingWasEnabled())->toBeFalse();
});

it('can determine if cpu throttling was enabled', function () {
    expect($this->lighthouseResult->cpuThrottlingWasEnabled())->toBeFalse();
});

it('can get the user agent', function () {
    expect($this->lighthouseResult->userAgent())->toContain('Chrome-Lighthouse');
});

it('can get all audit names', function () {
    expect($this->lighthouseResult->auditNames())->toHaveCount(152);

    expect($this->lighthouseResult->auditNames()[0])->toEqual('is-on-https');
});

it('can get all audits', function () {
    assertMatchesSnapshot($this->lighthouseResult->audits());
});

it('can get a specific audit', function () {
    $audit = $this->lighthouseResult->audit('first-contentful-paint');

    expect($audit['title'])->toEqual('First Contentful Paint');
    expect($audit['displayValue'])->toEqual('1.3 s');
});

it('will throw an exception when getting a non-existing audit', function () {
    $this->lighthouseResult->audit('non-existing-audit-name');
})->throws(AuditDoesNotExist::class);

it('can get the first contentful paint values', function () {
    expect($this->lighthouseResult)
        ->firstContentfulPaintInMs()->toEqual(1262.95)
        ->formattedFirstContentfulPaint()->toEqual('1.3 s');
});

it('can get the largest contentful paint values', function () {
    expect($this->lighthouseResult)
        ->largestContentfulPaintInMs()->toEqual(1262.951)
        ->formattedLargestContentfulPaint()->toEqual('1.3 s');
});

it('can get the speed index', function () {
    expect($this->lighthouseResult)
        ->speedIndexInMs()->toEqual(1258)
        ->formattedSpeedIndex()->toEqual('1.3 s');
});

it('can get the total blocking time', function () {
    expect($this->lighthouseResult)
        ->totalBlockingTimeInMs()->toEqual(0)
        ->formattedTotalBlockingTime()->toEqual('0 ms');
});

it('can get the time to interactive', function () {
    expect($this->lighthouseResult)
        ->timeToInteractiveInMs()->toEqual(1262.9499999389648)
        ->formattedTimeToInteractive()->toEqual('1.3 s');
});

it('can get the cumulative layout shift', function () {
    expect($this->lighthouseResult)
        ->cumulativeLayoutShift()->toEqual(0)
        ->formattedCumulativeLayoutShift()->toEqual(0);
});

it('can get the lighthouse version', function () {
    expect($this->lighthouseResult->lighthouseVersion())->toEqual('9.6.8');
});

it('can get the total page size in bytes', function () {
    expect($this->lighthouseResult->totalPageSizeInBytes())->toEqual(850);
});

it('can get the benchmark index', function () {
    expect($this->lighthouseResult->benchmarkIndex())->toEqual(2670);
});
