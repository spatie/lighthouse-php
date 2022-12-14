---
title: Introduction
weight: 1
---

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/lighthouse-php.svg?style=flat-square)](https://packagist.org/packages/spatie/lighthouse-php)
[![Tests](https://github.com/spatie/lighthouse-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/spatie/lighthouse-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/lighthouse-php.svg?style=flat-square)](https://packagist.org/packages/spatie/lighthouse-php)

[Google Lighthouse](https://developer.chrome.com/docs/lighthouse/overview/) is an open-source, automated tool for improving the quality of web pages. It has audits for performance, accessibility, progressive web apps, SEO and more.

This package makes it easy to run Lighthouse using PHP. Here's an example on how to get the scores of the five categories of audits that Lighthouse offers.

```php
use Spatie\Lighthouse\Lighthouse;

// returns an instance of Spatie\Lighthouse\LighthouseResult
$result = Lighthouse::url('https://example.com')->run();

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

It's easy to configure various options:

```php
use Spatie\Lighthouse\Lighthouse;
use Spatie\Lighthouse\Enums\Category;

Lighthouse::url('https://example.com')
    ->userAgent('My user agent')
    ->headers(['MyExtraHeader' => 'HeaderValue'])
    ->categories(Category::Performance, Category::Accessibility)
    ->throttleCpu()
    ->run();
```

Here's how you can get the results of an audit:

```php
$result->audit('first-contentful-paint') // returns this array

/*
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

You can also write a full HTML report to disk:

```php
$result->saveHtml($pathToReport)
```
