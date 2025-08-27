<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use Exception;

/**
 * Check if the variable is an empty string/file/array/null.
 * 
 * @param mixed $var
 * 
 * @return bool
 */
function varIsEmpty(mixed $var): bool
{
    if (\is_null($var)) {
        return true;
    }

    if (\in_array($var, [true, false, 'true', 'false'], true)) {
        return false;
    }

    if (\filter_var($var, FILTER_VALIDATE_INT) !== false) {
        return false;
    }

    if (\filter_var($var, FILTER_VALIDATE_FLOAT) !== false) {
        return false;
    }

    if (\is_array($var) && \count($var) === 0) {
        return true;
    }

    if (\is_resource($var) && varFileSize($var) === 0) {
        return true;
    }

    if (\is_string($var) && varStrSize($var) === 0) {
        return true;
    }

    return false;
}

/**
 * Get the size of the given variable's value.
 *
 * @param mixed $var
 *
 * @return int|float
 */
function varSize(mixed $var): int|float
{
    if (\in_array($var, [true, false, 'true', 'false'], true)) {
        return (int) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_INT) !== false) {
        return (int) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_FLOAT) !== false) {
        return (float) $var;
    }

    if (\is_array($var)) {
        return \count($var);
    }

    if (\is_resource($var)) {
        return varFileSize($var);
    }

    return varStrSize((string) $var);
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
 * @return int
 */
function varStrSize(string $var): int
{
    if (\is_file($var)) {
        return \filesize($var);
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
 * @param mixed $var
 *
 * @return mixed
 */
function varEvaluateType(mixed $var)
{
    if (!\is_string($var)) {
        return $var;
    }

    if (\str_replace(' ', '', $var) === '') {
        return $var;
    }

    $varLowered = \strtolower($var);

    if ($varLowered == 'null') {
        return null;
    }

    if (\in_array($varLowered, [true, false, 'true', 'false'], true)) {
        return (bool) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_INT) !== false) {
        return (int) $var;
    }

    if (\filter_var($var, FILTER_VALIDATE_FLOAT) !== false) {
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
    if (\filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
        return (float) $size;
    }

    if (\filter_var($size, FILTER_VALIDATE_INT) !== false) {
        return (int) $size;
    }

    if (!\is_string($size)) {
        return null;
    }

    $size           =   \str_replace(' ', '', $size);
    $sizeUnit       =   \substr($size, -2);
    $sizeUnit       =   \strtoupper($sizeUnit);
    $sizeInNumeric  =   \substr($size, 0, \strlen($size) - 2);

    if (\filter_var($sizeInNumeric, FILTER_VALIDATE_FLOAT) === false && \filter_var($sizeInNumeric, FILTER_VALIDATE_INT) === false) {
        return null;
    }

    $sizeInNumeric = (float) $sizeInNumeric;

    return (int) match ($sizeUnit) {
        'KB'    =>  $sizeInNumeric * 1024,
        'MB'    =>  $sizeInNumeric * 1024 * 1024,
        'GB'    =>  $sizeInNumeric * 1024 * 1024 * 1024,
        'TB'    =>  $sizeInNumeric * 1024 * 1024 * 1024 * 1024,
        default =>  throw new Exception("[Developer][Exception]: The given file size unit [{$sizeUnit}] is invalid/unsupported.")
    };
}
