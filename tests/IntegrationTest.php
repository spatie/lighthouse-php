<?php

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Lighthouse;

it('can get the scores of a real site', function () {
    $scores = Lighthouse::url('https://freek.dev')->getResult()->scores();

    expect($scores)->toHaveKeys(Category::values());
});
