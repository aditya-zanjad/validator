<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Rules\Date;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\TypeFile;
use AdityaZanjad\Validator\Rules\TypeJson;
use AdityaZanjad\Validator\Rules\TypeArray;
use AdityaZanjad\Validator\Rules\TypeString;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use AdityaZanjad\Validator\Rules\TypeInteger;

/**
 * @version 1.0
 */
class Validate
{
    public static function __callStatic($name, $arguments)
    {
        $result = static::$name(...$arguments);

        if ($result !== true) {
            return false;
        }

        return true;
    }

    public static function isString($value, string $regex = ''): bool
    {
        return (new TypeString($regex))->check('', $value);
    }

    public static function isArray($value)
    {
        return (new TypeArray())->check('', $value);
    }

    public static function isBoolean($value)
    {
        return (new TypeBoolean())->check('', $value);
    }

    public static function isInteger($value)
    {
        return (new TypeInteger())->check('', $value);
    }

    public static function isFloat($value)
    {
        return (new Numeric())->check('', $value);
    }

    public static function isFile($value)
    {
        return (new TypeFile())->check('', $value);
    }

    public static function isJson($value)
    {
        return (new TypeJson())->check('', $value);
    }

    public static function IsDate($value, string $format = '')
    {
        return (new Date($format))->check('', $value);
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
