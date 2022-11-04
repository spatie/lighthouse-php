<?php

use Spatie\Lighthouse\Support\Arr;

it('can get an element from an array', function (string $key, mixed $expectedResult) {
    $array = [
        'a' => 'value-a',
        'b' => [
            'c' => 'value-c',
            'd' => 'value-d',
        ],
    ];

    expect(Arr::get($array, $key))->toEqual($expectedResult);
})->with([
    ['a', 'value-a'],
    ['b', ['c' => 'value-c', 'd' => 'value-d']],
    ['b.c', 'value-c'],
    ['unknown', null],
]);
