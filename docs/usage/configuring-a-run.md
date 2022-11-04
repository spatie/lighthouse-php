---
title: Configuring a run
weight: 2
---

There are various methods to  configure how Lighthouse should run. You can use these between the `url()` and  `run()`. Here's an example where we set a custom user agent.

```php
use Spatie\Lighthouse\Lighthouse;
// returns an instance of Spatie\Lighthouse\LighthouseResult
$result = Lighthouse::url('https://example.com')
    ->userAgent('my-custom-user-agent')
    ->run();
```

## Only run audits for certain categories

By default, Lighthouse will run audits of all categories. To only run the audits of certain categories, call `categories()` and pass it one or more categories you are interested in.

```php
use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->categories(Category::BestPractices, Category::Seo)
    ->run();
```

## Customizing the user agent

To use a custom user agent, pass a string to `userAgent()`.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->userAgent('my-custom-user-agent')
    ->run();
```

## Setting extra headers

You can specify headers to will be sent along with all requests.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->headers(['MyExtraHeader' => 'value of the header'])
    ->run();
```

## Specifying the form factor

By default, Lighthouse will use a "Desktop" profile to run audits. You can change this to "mobile" using the `formFactor()` method.

```php
use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->formFactor(FormFactor::Mobile)
    ->run();
```

## Enable throttling

By default, Lighthouse will not throttle CPU and connection speed.

To enable throttling, use these methods.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->throttleCpu()
    ->throttleNetwork()
    ->run();
```

## Customize the Lighthouse configuration

To have fine-grained control of which options will be sent to lighthouse, you can pass an array of options to  `withConfig`.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->withConfig($arrayWithOptions)
    ->run();
```

If you don't call this method, we'll use these options by default.

```php
[
    'extends' => 'lighthouse:default',
    'settings' => [
        'onlyCategories' => Category::values(),
        'emulatedFormFactor' => 'desktop',
        'output' => ['json', 'html'],
        'disableNetworkThrottling' => true,
        'disableCpuThrottling' => true,
        'throttlingMethod' => 'provided',
    ],
];
```

To get a hold of the default options, you can call `defaultLighthouseConfig()`

## Customize the Chrome options

Under the hood, Lighthouse will run an instance of Chrome to run the audits. You can customize the options give to Chrome using `withChromeOptions()`

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->withChromeOptions($arrayWithOptions)
    ->run();
```

If you don't call this method, we'll use these options by default.

```php
[
    'chromeFlags' => [
        '--headless',
        '--no-sandbox',
],
```

To get a hold of the default options, you can call `defaultChromeOptions()`

