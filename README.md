<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=lighthouse-php">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/lighthouse-php/html/dark.webp">
        <img alt="Logo for lighthouse-php" src="https://spatie.be/packages/header/lighthouse-php/html/light.webp">
      </picture>
    </a>

<h1>Run Google Lighthouse using PHP</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/lighthouse-php.svg?style=flat-square)](https://packagist.org/packages/spatie/lighthouse-php)
[![Tests](https://github.com/spatie/lighthouse-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/spatie/lighthouse-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/lighthouse-php.svg?style=flat-square)](https://packagist.org/packages/spatie/lighthouse-php)
    
</div>

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

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/lighthouse-php.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/lighthouse-php)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/lighthouse-php).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

This package contains code copied from [Laravel's `Arr` class](https://github.com/laravel/framework/blob/9.x/src/Illuminate/Collections/Arr.php).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
