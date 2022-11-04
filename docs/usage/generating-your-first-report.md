---
title: Generating your first report
weight: 1
---

With the package installed, this is how you can get run lighthouse. 

```php
use Spatie\Lighthouse\Lighthouse;

// returns an instance of Spatie\Lighthouse\LighthouseResult
$result = Lighthouse::url('https://example.com')->run();
```

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

Here's how you can get the results of an audit:

```php
$result->audit('first-contentful-paint');

/* returns this array
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

To get the results of all audits, call `audits()`.

```php
// returns an array with the results of all audits
$result->audits();
```
