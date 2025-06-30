<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Rules\Date;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\TypeArray;
use AdityaZanjad\Validator\Rules\TypeFile;
use AdityaZanjad\Validator\Rules\TypeJson;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use AdityaZanjad\Validator\Rules\TypeInteger;
use AdityaZanjad\Validator\Rules\TypeString;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class Validate
{
    /**
     * Intercept static function return values for this class.
     *
     * @param   string  $name
     * @param   array   $arguments
     * 
     * @return  bool
     */
    public static function __callStatic($name, $arguments): bool
    {
        $result = static::$name(...$arguments);

        if (\is_bool($result)) {
            return $result;
        }

        return false;
    }

    /**
     * Validate whether the given value is a string or not.
     *
     * @param   mixed     $value
     * @param   string    $regex
     * 
     * @return  bool
     */
    public static function isString($value, string $regex = ''): bool
    {
        return (new TypeString($regex))->check('', $value);
    }

    public static function isArray($value)
    {
        return (new TypeArray())->check('', $value);
    }

    public static function isBoolean($value, array $expectedBooleans = [])
    {
        return (new TypeBoolean(...$expectedBooleans))->check('', $value);
    }

    public static function isInteger($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        return true;
    }

    public static function isFloat($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        return true;
    }

    public static function isFile($value)
    {
        return (new TypeFile())->check('', $value);
    }

    public static function isJson($value, array $options = [])
    {
        if (!is_string($value)) {
            return false;
        }

        $options['depth'] ??= 1024;
        $options['flags'] ??= 0;

        if (\function_exists('\\json_validate')) {
            return \json_validate($value, $options['depth'], $options['flags']);
        }

        \json_decode($value, true, $options['depth'], $options['flags']);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return true;
    }

    public static function IsDate($value, string $format = '')
    {
        $dateParsed = parseDateTime($value, $format);

        if ($dateParsed === false) {
            return false;
        }

        return true;
    }

    public static function isEmail($value)
    {
        return (new Email())->check('', $value);
    }

    public static function isUrl($value)
    {
        return (new Url())->check('', $value);
    }
}
