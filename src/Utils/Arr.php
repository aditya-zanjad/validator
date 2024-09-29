<?php

namespace AdityaZanjad\Validator\Utils;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Convert the given multi-dimensional array structure to dot notation path array structure.
 *
 * @param   array<int|string, mixed> $arr
 *
 * @return  array<int|string, mixed>
 */
function array_to_dot(array $arr): array
{
    if (empty($arr)) {
        return $arr;
    }

    $arr    =   new RecursiveArrayIterator($arr);
    $arr    =   new RecursiveIteratorIterator($arr, RecursiveIteratorIterator::SELF_FIRST);

    $result     =   [];
    $nestedKeys =   [];

    foreach ($arr as $key => $value) {
        $nestedKeys[$arr->getDepth()] = $key;

        if (is_array($value) && !empty($value)) {
            continue;
        }

        $nestedKeys                         =   array_slice($nestedKeys, 0, $arr->getDepth() + 1);
        $result[implode('.', $nestedKeys)]  =   $value;
    }

    return $result;
}

/**
 * Fetch the element from the array based on the given array dot notation path.
 *
 * @param   array<int|string, mixed>    &$arr
 * @param   string                      $path
 *
 * @return  bool
 */
function array_value_exists(array &$arr, string $path): bool
{
    $path = explode('.', $path);

    foreach ($path as $param) {
        if (!isset($arr[$param])) {
            return false;
        }

        $arr = &$arr[$param];
    }

    return true;
}

/**
 * Fetch the element from the array based on the given array dot notation path.
 *
 * @param   array<int|string, mixed>    &$arr
 * @param   string                      $path
 *
 * @return  mixed
 */
function array_value_get(array &$arr, string $path): mixed
{
    $path = explode('.', $path);

    foreach ($path as $param) {
        if (!isset($arr[$param])) {
            return null;
        }

        $arr = &$arr[$param];
    }

    return $arr;
}


/**
 * Get the first value from the given array.
 *
 * @param array &$arr
 *
 * @return mixed
 */
function array_value_first(array &$arr): mixed
{
    if (empty($arr)) {
        null;
    }

    return $arr[array_key_first($arr)];
}
