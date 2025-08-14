<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use Exception;

/**
 * Get the size of the given variable's value.
 *
 * @param   string  $type
 * @param   mixed   $var
 *
 * @throws  \Exception
 *
 * @return  int|float
 */
function varSize($var)
{
    $evaluatedVar = varEvaluateType($var);

    switch (\gettype($evaluatedVar)) {
        case 'integer':
        case 'float':
        case 'double':
            return $var;

        case 'bool':
        case 'boolean':
            return ((bool) $var) ? 1 : 0;

        case 'string':
            return varStringSize($var);

        case 'array':
            return \count($var);

        case 'resource':
            return varFileSize($var);

            // no break
        default:
            throw new Exception("[Developer][Exception]: The given parameter has an invalid data type.");
            break;
    }
}

/**
 * Get the size of a file from the given file path/resource
 *
 * @param mixed $var
 * 
 * @return null|int
 */
function varFileSize(mixed $var): ?int
{
    $metadata = \stream_get_meta_data($var);

    if ($metadata['wrapper_type'] !== 'plainfile') {
        return null;
    }

    $stats = \fstat($var);
    return $stats['size'];
}

/**
 * Get the size of the given string.
 *
 * @param string $var
 * 
 * @return void
 */
function varStringSize(string $var)
{
    if (is_file($var)) {
        return filesize($var);
    }

    if (\function_exists('\\mb_strlen')) {
        return \mb_strlen($var);
    }

    return \strlen($var);
}

/**
 * Find out the number of digits in a number.
 *
 * @param mixed $var
 *
 * @throws \Exception
 *
 * @return null|int
 */
function varDigits(mixed $var): ?int
{
    if (\filter_var($var, FILTER_VALIDATE_FLOAT) === false) {
        return null;
    }

    if (((int) $var) === 0) {
        return 1;
    }

    return (int) (\log((float) $var, 10) + 1);
}

/**
 * Evaluate the given string variable to its corresponding data type value.
 *
 * @param string $var
 *
 * @return mixed
 */
function varEvaluateType($var)
{
    if (!\is_string($var)) {
        return $var;
    }

    $varLowered = \strtolower($var);
    $varTrimmed = \str_replace(' ', '', $var);

    if ($varTrimmed === '' || $varLowered == 'null') {
        return null;
    }

    if (\in_array($varLowered, [true, false, 'true', 'false'], true)) {
        return (bool) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_INT) !== false || \is_numeric($var)) {
        return (int) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_FLOAT) !== false || \is_numeric($var)) {
        return (float) $var;
    }

    return $var;
}

/**
 * Prepare the size value depending on the provided parameter(s).
 * 
 * If the size value is either an integer
 *
 * @param mixed $size
 * 
 * @return mixed
 */
function varMakeSize(mixed $size): mixed
{
    if (filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
        return (float) $size;
    }

    if (filter_var($size, FILTER_VALIDATE_INT) !== false) {
        return (int) $size;
    }

    if (!\is_string($size)) {
        return null;
    }

    $size           =   \str_replace(' ', '', $size);
    $sizeUnit       =   \substr($size, -2);
    $sizeUnit       =   \strtoupper($sizeUnit);
    $sizeInNumeric  =   \substr($size, 0, \strlen($size) - 2);

    if (filter_var($sizeInNumeric, FILTER_VALIDATE_FLOAT) === false && filter_var($sizeInNumeric, FILTER_VALIDATE_INT) === false) {
        return null;
    }

    return (int) match ($sizeUnit) {
        'B'     =>  $sizeInNumeric,
        'KB'    =>  $sizeInNumeric * 1024,
        'MB'    =>  $sizeInNumeric * 1024 * 1024,
        'GB'    =>  $sizeInNumeric * 1024 * 1024 * 1024,
        default =>  throw new Exception("[Developer][Exception]: The given file size unit [{$sizeUnit}] is invalid/unsupported.")
    };
}
