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

it('can get a nullish element from an array', function () {
    $array = [
        'a' => null,
        'key.with.dots' => null,
        'key' => [
            'with' => [
                'dots' => 'value',
            ],
        ],
    ];

    expect(Arr::get($array, 'a', 'default'))->toBeNull();
    expect(Arr::get($array, 'key.with.dots', 'default'))->toBeNull();
});

it('can return a default value for non-existing keys', function () {
    $array = [
        'a' => 'value',
    ];

    expect(Arr::get($array, 'unknown', 'default'))->toBe('default');
});

it('can remove an element from an array', function (string|array $key, array $expectedResult) {
    $array = [
        'a' => 'value-a',
        'b' => [
            'c' => 'value-c',
            'd' => 'value-d',
        ],
        'e' => null,
    ];

    Arr::forget($array, $key);

    expect($array)->toEqual($expectedResult);
})->with([
    ['a', ['b' => ['c' => 'value-c', 'd' => 'value-d'], 'e' => null]],
    ['b', ['a' => 'value-a', 'e' => null]],
    ['b.c', ['a' => 'value-a', 'b' => ['d' => 'value-d'], 'e' => null]],
    ['b.c.unknown', ['a' => 'value-a', 'b' => ['c' => 'value-c', 'd' => 'value-d'], 'e' => null]],
    ['e', ['a' => 'value-a', 'b' => ['c' => 'value-c', 'd' => 'value-d']]],
    [['a','b'], ['e' => null]],
    ['unknown', ['a' => 'value-a', 'b' => ['c' => 'value-c', 'd' => 'value-d'], 'e' => null]],
    ['unknown.unknown', ['a' => 'value-a', 'b' => ['c' => 'value-c', 'd' => 'value-d'], 'e' => null]],
]);
