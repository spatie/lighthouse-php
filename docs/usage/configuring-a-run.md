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

## Only run audits in certain categories

By default, Lighthouse will run audits of all categories. To only run the audits of certain categories, call `categories()` and pass it one or more categories you are interested in.

```php
use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->categories(Category::BestPractices, Category::Seo)
    ->run();
```

## Skip certain audits

To lower Lighthouse's execution time, you can opt to skip audits by passing their names to `skipAudits`.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->skipAudits(['is-on-https', 'service-worker'])
    // ...
```

## Only run specific audits

You can opt to run only specific audits using the `onlyAudits()` method.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    // only these two audits can be run
    ->onlyAudits(['is-on-https', 'service-worker'])
    // ...
```

You cannot use `skipAudits` and `onlyAudits` at the same time.

If you want to run a specific audit and an entire other category. You must call `categories()` after `onlyAudits()`.

In this example we are going to run the `is-on-https` audit together with all audits from the `Seo` category.

```php
use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->onlyAudits('is-on-https')
    ->categories(Category::Seo)
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

To get a hold of the default options, you can call `defaultLighthouseConfig()`.

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

To get a hold of the default options, you can call `defaultChromeOptions()`.

## Setting a timeout

By default, if the lighthouse process takes more than 60 seconds it will be aborted and a `Symfony\Component\Process\Exception\ProcessTimedOutException` will be thrown.

You can adjust the timeout using the `timeoutInSeconds()` method.

```php
use Spatie\Lighthouse\Lighthouse;

$result = Lighthouse::url('https://example.com')
    ->timeoutInSeconds(120) // allow to run 120 seconds
    ->run();
```
