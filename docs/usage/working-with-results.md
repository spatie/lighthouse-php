---
title: Working with results
weight: 3
---

Running Lighthouse will return an instance of `Spatie\Lighthouse\LighthouseResult`. On this page, we'll cover the various methods you can call on `LighthouseResult`

```php
use Spatie\Lighthouse\Lighthouse;

// instance of `Spatie\Lighthouse\LighthouseResult`
$result = Lighthouse::url('https://example.com')
```

## Getting the category scores

You can use the `scores` method to get scores of the five categories Lighthouse runs audits for.

```php
$result->scores(); // returns an array like this one:
/*
 * [
 *    'performance' => 98,
 *    'accessibility' => 83,
 *    'best-practices' => 90,
 *    'seo' => 92,
 *    'pwa' => 43,  
 * ]
 */
```

## Getting audit results

You can get an array with names of all audits using the `auditNames()` method.

```php
$allAuditNames = $result->auditNames();
```

Here's how you can get the results of an audit:

```php
$result->audit('first-contentful-paint') 

/*  returns an array like this one.
 * 
 * [
 *     'id' => 'first-contentful-paint'
 *     'title' => 'First Contentful Paint'
 *     'score' => 0.98
 *     'scoreDisplayMode' => 'numeric'
 *     'numericValue' => 1262.95
 *     'numericUnit' => 'millisecond'
 *     'displayValue' => '1.3 s'
 * ]
 */
```

To get the results of all audits in one go, call, `audits()`

```php
$result->audits();
```

## Getting the configuration used to generate the result

The result will also contain the settings that were used to run Lighthouse.

```php
// returns an array
$settings = $result->configSettings();
```

To get a specific config setting, you can pass a key in dot notation to `configSettings()`. Here's how you can the used browser width.

```php
$width = $result->configSettings('screenEmulation.width');
```

There are also a couple of convenience methods to quickly get interesting pieces of config that you can call on `$result`:

- `formFactor()`: returns `desktop` or `mobile`
- `userAgent()`: returns the user agent that was used
- `networkThrottlingWasEnabled()`: returns a boolean
- `cpuThrottlingWasEnabled`: return a boolean

## Getting the raw results

To get the raw results that were returned from Lighthouse, you can use the `rawResults` method.

```php
// returns an array with everything returned from Lighthouse

$result->rawResults();
```

You can a specific value from the raw results by passing a key using dot notation.

```php
// returns the lighthouse version
$result->rawResults('lhr.lighthouseVersion')
```
