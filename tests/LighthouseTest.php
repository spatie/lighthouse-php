<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Exceptions\CouldNotRunLighthouse;
use Spatie\Lighthouse\Exceptions\InvalidCategory;
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
})->throws(InvalidCategory::class);

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
