
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Programmatically run Google Lighthouse using PHP

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

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/lighthouse-php.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/lighthouse-php)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/lighthouse-php
```

This package relies on the `lighthouse` and `chrome-launcher` js package being available on your system. In most cases you can accomplish this by issues these commands in your project.

```bash
npm install lighthouse
npm install chrome-launcher
```

## Usage

Coming soon...

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
