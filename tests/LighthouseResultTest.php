<?php

use Spatie\Lighthouse\Enums\Device;
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

it('can get the json results', function () {
    assertMatchesSnapshot($this->lighthouseResult->json());
});

it('can get a certain property of the json results', function () {
    expect($this->lighthouseResult->json('categories.accessibility.score'))->toEqual(0.92);
});

it('the get methods produces the same results as json', function () {
    expect($this->lighthouseResult->json('categories.accessibility.score'))
        ->toEqual($this->lighthouseResult->get('categories.accessibility.score'));
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
    expect($this->lighthouseResult->rawResults)->toEqual(getJsonStub('example-com-result'));
});

it('can get the configSettings', function () {
    assertMatchesSnapshot($this->lighthouseResult->configSettings());

    expect($this->lighthouseResult->configSettings('screenEmulation.mobile'))->toBeTrue();
});

it('can get the emulated form factor', function () {
    expect($this->lighthouseResult->emulatedFormFactor())->toEqual(Device::Desktop);
});

it('can determine if network throttling was enabled', function () {
    expect($this->lighthouseResult->networkThrottlingWasEnabled())->toBeFalse();
});

it('can determine if cpu throttling was enabled', function () {
    expect($this->lighthouseResult->cpuThrottlingWasEnabled())->toBeFalse();
});
