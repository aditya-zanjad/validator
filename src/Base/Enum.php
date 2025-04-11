<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use ReflectionClass;

use function AdityaZanjad\Validator\Utils\str_after;
use function AdityaZanjad\Validator\Utils\arr_map_with_keys;

/**
 * @version 2.0
 */
class Enum extends NonInstantiable
{
    /**
     * Get a list of all of the constants defined in the current class.
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return arr_map_with_keys(static::reflectionClass()->getConstants(), function ($key, $value) {
            $modifiedKey = static::resolveName($key);
            return [$modifiedKey => $value];
        });
    }

    /**
     * Get the names of all the constants defined in the current class.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_map(function ($key) {
            return static::resolveName($key);
        }, array_keys(static::all()));
    }

    /**
     * Get values of all the constants defined in the current class.
     *
     * @return array<int, mixed>
     */
    public static function values(): array
    {
        return array_values(static::all());
    }

    /**
     * Get the name of the first constant whose value matches with the given parameters.
     *
     * @param   mixed   $val
     * @param   bool    $strict
     *
     * @return  null|string
     */
    public static function keyOf(mixed $val, bool $strict = true)
    {
        $all    =   static::all();
        $key    =   null;

        foreach ($all as $key => $value) {
            $result = $strict ? $value === $val : $value == $val;

            if ($result) {
                $key = static::resolveName($key);
            }
        }

        return $key;
    }

    /**
     * Get the names of all the constants whose values match with the given parameters.
     *
     * @param   mixed   $val
     * @param   bool    $strict
     *
     * @return array<int, string>
     */
    public static function keysOf(mixed $val, bool $strict = true)
    {
        $all    =   static::all();
        $keys   =   [];

        foreach ($all as $key => $value) {
            $result = $strict ? $value === $val : $value == $val;

            if ($result) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Check whether or not the given constant exists in the current class.
     *
     * @param   string  $key
     * @param   bool    $upperCased
     *
     * @return  bool
     */
    public static function exists(string $key, bool $upperCased = true): bool
    {
        $transformedKey =   $upperCased ? strtoupper($key) : strtolower($key);
        $currentClass   =   static::class;

        return defined("$currentClass::{$transformedKey}") || defined("{$currentClass}::___{$transformedKey}");
    }

    /**
     * Get the value of the constant by the given name.
     *
     * If the null value is returned, it means that the constant does exist.
     *
     * @param   string  $key
     * @param   bool    $upperCased
     *
     * @return  mixed
     */
    public static function valueOf(string $key, bool $upperCased = true)
    {
        $transformedKey =   $upperCased ? strtoupper($key) : strtolower($key);
        $currentClass   =   static::class;

        if (defined("{$currentClass}::{$transformedKey}")) {
            return constant("{$currentClass}::{$transformedKey}");
        }

        if (defined("{$currentClass}::___{$transformedKey}")) {
            return constant("{$currentClass}::___{$transformedKey}");
        }

        return null;
    }

    /**
     * Get an instance of the '\ReflectionClass'.
     *
     * @return \ReflectionClass
     */
    final protected static function reflectionClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    /**
     * Resolve the name of the constant to its supposed form if required.
     *
     * @param string $name
     *
     * @return string
     */
    protected static function resolveName(string $name)
    {
        if (str_starts_with($name, '___')) {
            $name = str_after($name, '___');
        }

        return $name;
    }
}
