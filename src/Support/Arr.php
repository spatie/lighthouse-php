<?php

namespace Spatie\Lighthouse\Support;

class Arr
{
    public static function get(array $array, string|int|null $key, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function without(array $array, string $value)
    {
        $key = array_search($value, $array);

        if ($key !== false) {
            unset($array[$key]);
        }

        return $array;
    }
}
