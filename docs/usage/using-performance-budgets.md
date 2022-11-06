---
title: Using performance budgets
weight: 5
---

Lighthouse has an interesting feature where you can specify so-called budgets. You can, for example, specify the maximum amount of time for the 'time to interactive' metric, or the maximum amount (measured in Kb) of JavaScript your page may contain.

You can learn more on this feature in the 'Use Lighthouse for performance
budgets [at web.dev](https://web.dev/use-lighthouse-for-performance-budgets/).

You can pass your performance budget as an array to the `budgets()` method.

```php
$result = Lighthouse::url($url)
    ->budgets([
        [
            'timings' => [
                [
                    'metric' => 'interactive',
                    'budget' => 5000,
                ],
            ],
        ],
    ])
    ->run();
```

In the [HTML report](/docs/lighthouse-php/v1/usage/saving-an-html-report), you can see the results of your budget.

![screenshot](https://spatie.be/docs/lighthouse-php/v1/images/budget.jpg).

You can get an array with the results of the budget calling `budgetResults()`.

```php
$result->budgetResults();
```

It will return an array much like this one:

```php
[
    'performance-budget' => [
        'id' => 'performance-budget',
        'title' => 'Performance budget',
        'description' => 'Keep the quantity and size of network requests under the targets set by the provided performance budget. [Learn more](https://developers.google.com/web/tools/lighthouse/audits/budgets).',
        'score' => null,
        'scoreDisplayMode' => 'informative',
        'details' => [
            'type' => 'table',
            'headings' => [],
            'items' => [],
        ],
    ],
    'timing-budget' => [
        'id' => 'timing-budget',
        'title' => 'Timing budget',
        'description' => 'Set a timing budget to help you keep an eye on the performance of your site. Performant sites load fast and respond to user input events quickly. [Learn more](https://developers.google.com/web/tools/lighthouse/audits/budgets).',
        'score' => null,
        'scoreDisplayMode' => 'informative',
        'details' => [
            'type' => 'table',
            'headings' => [
                0 => [
                    'key' => 'label',
                    'itemType' => 'text',
                    'text' => 'Metric',
                ],
                1 => [
                    'key' => 'measurement',
                    'itemType' => 'ms',
                    'text' => 'Measurement',
                ],
                2 => [
                    'key' => 'overBudget',
                    'itemType' => 'ms',
                    'text' => 'Over Budget',
                ],
            ],
            'items' => [
                0 => [
                    'metric' => 'interactive',
                    'label' => 'Time to Interactive',
                    'measurement' => 9900,
                    'overBudget' => 4900,
                ],
            ],
        ],
    ],
];
```
