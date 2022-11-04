<?php

use Spatie\Lighthouse\Exceptions\InvalidUrl;
use Spatie\Lighthouse\Lighthouse;

use function Spatie\Snapshots\assertMatchesSnapshot;

beforeEach(function () {
    $this->lighthouse = Lighthouse::url('https://example.com');
});

it('will use the default config by default', function () {
    $arguments = $this->lighthouse->lighthouseScriptArguments();

    assertMatchesSnapshot($arguments);
});

it('will throw an exception when passing an invalid url', function () {
    Lighthouse::url('invalid-url');
})->throws(InvalidUrl::class);

it('can get the scores of a real site', function () {
    Lighthouse::url('https://freek.dev')->getResult()->scores();
});
