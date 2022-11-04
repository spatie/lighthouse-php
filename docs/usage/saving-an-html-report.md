---
title: Saving an HTML report
weight: 4
---

Lighthouse can generate a nicely formatted HTML report with the scores and results of all audits.

```php
use Spatie\Lighthouse\Lighthouse;

Lighthouse::url('https://example.com')
    ->run()
    ->saveHtml($pathToFile);
```

This is how that report looks like in the browser.

![screenshot](https://spatie.be/docs/lighthouse-php/v1/images/report.jpg)

To get the HTML, without save it to a file, simply call `html()`.

```php
use Spatie\Lighthouse\Lighthouse;

$html = Lighthouse::url('https://example.com')
    ->run()
    ->html();
```
