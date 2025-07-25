<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use Exception;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Get the first element of the array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return mixed
 */
function arr_first(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    if (\function_exists('\\array_key_first')) {
        return $arr[\array_key_first($arr)];
    }

    return \array_shift($arr);
}

/**
 * Get the first element of the array.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return mixed
 */
function arr_last(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    if (\function_exists('\\array_key_last')) {
        return $arr[\array_key_last($arr)];
    }

    return \end($arr);
}

/**
 * Convert the given multi-dimensional array structure to dot notation path array structure.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return array<int|string, mixed>
 */
function arr_dot(array $arr): array
{
    if (empty($arr)) {
        return $arr;
    }

    if (!\class_exists(RecursiveArrayIterator::class) || !\class_exists(RecursiveIteratorIterator::class)) {
        $fn = function (array $arr, string $context = '') use (&$fn) {
            $result = [];

            foreach ($arr as $key => $value) {
                if (!\is_array($value)) {
                    $result["{$context}{$key}."] = $value;
                    continue;
                }

                foreach ($fn($value, "{$context}{$key}.") as $nestedKey => $nestedValue) {
                    $result[$nestedKey] = $nestedValue;
                }
            }

            return $result;
        };

        return $fn($arr);
    }

    $arr        =   new RecursiveArrayIterator($arr);
    $arr        =   new RecursiveIteratorIterator($arr, RecursiveIteratorIterator::SELF_FIRST);
    $result     =   [];
    $nestedKeys =   [];

    foreach ($arr as $key => $value) {
        $nestedKeys[$arr->getDepth()] = $key;

        if (\is_array($value) && !empty($value)) {
            continue;
        }

        $nestedKeys                         =   \array_slice($nestedKeys, 0, $arr->getDepth() + 1);
        $result[\implode('.', $nestedKeys)] =   $value;
    }

    return $result;
}

/**
 * Get the nested array paths in the form of dot notations.
 *
 * @param   array<int|string, mixed> $arr
 *
 * @return  array<int, int|string>
 */
function arr_dot_paths(array $arr): array
{
    if (empty($arr)) {
        return $arr;
    }

    if (!\class_exists(RecursiveArrayIterator::class) || !\class_exists(RecursiveIteratorIterator::class)) {
        $fn = function (array $arr, $context = '') use (&$fn) {
            $result = [];

            foreach ($arr as $key => $value) {
                $path = "{$context}{$key}";

                if (\is_array($value)) {
                    $nested =   $fn($value, "{$path}.");
                    $result =   \array_merge($result, $nested);

                    continue;
                }

                $result[] = $path;
            }

            return $result;
        };

        return $fn($arr);
    }

    $arr        =   new RecursiveArrayIterator($arr);
    $arr        =   new RecursiveIteratorIterator($arr, RecursiveIteratorIterator::SELF_FIRST);
    $result     =   [];
    $nestedKeys =   [];

    foreach ($arr as $key => $value) {
        $nestedKeys[$arr->getDepth()] = $key;

        if (\is_array($value) && !empty($value)) {
            continue;
        }

        $nestedKeys =   \array_slice($nestedKeys, 0, $arr->getDepth() + 1);
        $result[]   =   \implode('.', $nestedKeys);
    }

    return $result;
}

/**
 * Check if the given dot notation array exists or not OR is equal to Null or not in the given array.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   string                      $path
 *
 * @return  bool
 */
function arr_exists(array $arr, string $path): bool
{
    $ref    =   &$arr;
    $keys   =   \explode('.', $path);

    foreach ($keys as $key) {
        if (!\array_key_exists($key, $ref) || !\is_array($ref[$key])) {
            return false;
        }

        $ref = &$ref[$key];
    }

    return true;
}

/**
 * Check if a particular dot notation path exists or not and/or if its value is equal to NULL or not.
 *
 * @param   array<int|string, mixed>    $arr
 * @param   string                      $path
 *
 * @return  bool
 */
function arr_nulled(array $arr, string $path): bool
{
    $ref    =   &$arr;
    $keys   =   \explode('.', $path);

    foreach ($keys as $key) {
        if (!isset($ref[$key])) {
            return true;
        }

        $ref = &$ref[$key];
    }

    return false;
}

/**
 * Fetch the element from the array based on the given array dot notation path.
 *
 * @param   array<int|string, mixed>    &$arr
 * @param   string                      $path
 *
 * @return  mixed
 */
function arr_get(array &$arr, string $path): mixed
{
    $keys = \explode('.', $path);

    foreach ($keys as $key) {
        if (!isset($arr[$key])) {
            return null;
        }

        $arr = &$arr[$key];
    }

    return $arr;
}

/**
 * Check if the given array path exists & is not equal to null.
 *
 * @param   array   $arr
 * @param   string  $path
 *
 * @return  bool
 */
function arr_filled(array $arr, string $path): bool
{
    $ref    =   &$arr;
    $keys   =   \explode('.', $path);

    foreach ($keys as $key) {
        // The field must be set & not empty.
        if (!isset($ref[$key]) || empty($ref[$key])) {
            return false;
        }

        $ref = &$ref[$key];
    }

    return true;
}

/**
 * Check if the given array is an indexed array or not.
 *
 * @param array<int|string, mixed> $arr
 *
 * @return bool
 */
function arr_indexed(array $arr): bool
{
    if (empty($arr)) {
        return true;
    }

    if (\function_exists('\\array_is_list')) {
        return \array_is_list($arr);
    }

    return \array_keys($arr) === \range(0, \count($arr) - 1);
}

/**
 * Get the first key of an array.
 *
 * @param array $arr
 *
 * @return null|int|string
 */
function arr_first_key(array $arr)
{
    if (empty($arr)) {
        return null;
    }

    if (\function_exists('\\array_key_first')) {
        return \array_key_first($arr);
    }

    \reset($arr);
    return \key($arr);
}

/**
 * An alternative to PHP's built-in 'map()' function which also allows mapping over the keys as well.
 *
 * @param   array                                                               $arr
 * @param   callable(mixed $value, int|string $key): array<int|string, mixed>   $fn
 *
 * @return  array<int|string, mixed>
 */
function arr_map_v2(array $arr, callable $fn): array
{
    if (empty($arr)) {
        return [];
    }

    $mapped = [];

    foreach ($arr as $key => $value) {
        $result = $fn($value, $key);

        if (!\is_array($result)) {
            throw new Exception("[Developer][Exception]: The callback function must return an array.");
        }

        $firstKey           =   \function_exists('\\array_key_first') ? \array_key_first($result) : \key($result);
        $mapped[$firstKey]  =   $result[$firstKey];
    }

    return $mapped;
}
