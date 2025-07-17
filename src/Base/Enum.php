<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use Exception;
use ReflectionClass;

/**
 * @version 2.0
 */
class Enum
{
    /**
     * Make this class & its child classes completely non-instantiable.
     * 
     * @throws \Exception
     */
    final public function __construct()
    {
        throw new Exception("[Developer][Exception]: The class [" . static::class . "] is a non-instantiable one.");
    }

    /**
     * Get a list of all of the constants defined in the current class.
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return static::reflectionClass()->getConstants();
    }

    /**
     * Get the names of all the constants defined in the current class.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(static::all());
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
        $all        =   static::all();
        $keyFound   =   null;

        foreach ($all as $key => $value) {
            $result = $strict ? $value === $val : $value == $val;

            if ($result) {
                $keyFound = $key;
                break;
            }
        }

        return $keyFound;
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

        return defined("$currentClass::{$transformedKey}");
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

        if (!static::exists($key, $upperCased)) {
            return null;
        }

        return constant("{$currentClass}::{$transformedKey}");
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
}
