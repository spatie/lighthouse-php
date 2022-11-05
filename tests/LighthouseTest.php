<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Exceptions\CouldNotRunLighthouse;
use Spatie\Lighthouse\Exceptions\InvalidEnumValue;
use Spatie\Lighthouse\Exceptions\InvalidUrl;
use Spatie\Lighthouse\Lighthouse;
use function Spatie\Snapshots\assertMatchesSnapshot;

beforeEach(function () {
    $this->lighthouse = Lighthouse::url('https://example.com');
});

it('will use the default config by default', function () {
    $arguments = $this->lighthouse->lighthouseScriptArguments();

    assertMatchesSnapshot($arguments);
});

it('will throw an exception when passing an invalid url', function () {
    Lighthouse::url('invalid-url');
})->throws(InvalidUrl::class);

it('can get the default lighthouse config', function () {
    expect($this->lighthouse->defaultLighthouseConfig())->toBeArray();
});

it('can get the default chrome options', function () {
    expect($this->lighthouse->defaultChromeOptions())->toBeArray();
});

it('can set the categories that should be audited', function () {
    $this->lighthouse->categories(Category::Accessibility, Category::Performance);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toEqual([
        Category::Accessibility->value,
        Category::Performance->value,
    ]);
});

it('can accept the categories as an array', function () {
    $this->lighthouse->categories([Category::Accessibility, Category::Performance]);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toEqual([
        Category::Accessibility->value,
        Category::Performance->value,
    ]);
});

it('can accept the categories as strings', function () {
    $this->lighthouse->categories(Category::Accessibility->value, Category::Performance->value);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toEqual([
        Category::Accessibility->value,
        Category::Performance->value,
    ]);
});

it('can accept the categories a an array of strings', function () {
    $this->lighthouse->categories([Category::Accessibility->value, Category::Performance->value]);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toEqual([
        Category::Accessibility->value,
        Category::Performance->value,
    ]);
});

it('will throw an exception when passing an invalid category', function () {
    $this->lighthouse->categories('invalid-category');
})->throws(InvalidEnumValue::class);

it('can manually set the lighthouse config', closure: function () {
    $config = ['my-key' => 'my-value'];

    $this->lighthouse->withConfig($config);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig'))->toEqual($config);
});

it('can manually set the Chrome options', function () {
    $chromeOptions = ['my-key' => 'my-value'];

    $this->lighthouse->withChromeOptions($chromeOptions);

    expect($this->lighthouse->lighthouseScriptArguments('chromeOptions'))->toEqual($chromeOptions);
});

it('will thrown a dedicated exception when lighthouse cannot run', function () {
    $this->lighthouse->withConfig(['invalid-config'])->run();
})->throws(CouldNotRunLighthouse::class);

it('can set a form factor', function () {
    $this->lighthouse->formFactor(FormFactor::Mobile);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.emulatedFormFactor'))
        ->toEqual(FormFactor::Mobile->value);
});

it('can throttle cpu', function () {
    $this->lighthouse->throttleCpu();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.disableCpuThrottling'))->toBeFalse();
});

it('can disable throttling cpu', function () {
    $this->lighthouse->doNotThrottleCpu();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.disableCpuThrottling'))->toBeTrue();
});

it('can throttle the network', function () {
    $this->lighthouse->throttleNetwork();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.disableNetworkThrottling'))->toBeFalse();
});

it('can disable throttling the network', function () {
    $this->lighthouse->doNotThrottleNetwork();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.disableNetworkThrottling'))->toBeTrue();
});

it('can set the user agent', function () {
    $this->lighthouse->userAgent('my-user-agent');

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.emulatedUserAgent'))->toEqual('my-user-agent');
});

it('can be configured to run only one audit', function() {
    $this->lighthouse->onlyAudits('is-on-https');

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toBeNull();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyAudits'))->toEqual(['is-on-https']);
});

it('can be configured to run only a few audits', function() {
    $this->lighthouse->onlyAudits('is-on-https', 'structured-data');

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toBeNull();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyAudits'))->toEqual([
        'is-on-https',
        'structured-data',
    ]);
});

it('can be configured to run only a few audits using an array', function() {
    $this->lighthouse->onlyAudits(['is-on-https', 'structured-data']);

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyCategories'))->toBeNull();

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.onlyAudits'))->toEqual([
        'is-on-https',
        'structured-data',
    ]);
});

it('can skip an audit', function() {
   $this->lighthouse->skipAudits('is-on-https');

   expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.skipAudits'))
       ->toEqual(['is-on-https']);
});

it('can skip multiple audits', function() {
    $this->lighthouse->skipAudits('is-on-https', 'service-worker');

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.skipAudits'))
        ->toEqual(['is-on-https', 'service-worker']);
});

it('can skip multiple audits using an array', function() {
    $this->lighthouse->skipAudits('is-on-https', 'service-worker');

    expect($this->lighthouse->lighthouseScriptArguments('lighthouseConfig.settings.skipAudits'))
        ->toEqual(['is-on-https', 'service-worker']);
});
