<?php

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
function varSize(mixed $var): int|float
{
    $varType = gettype($var);

    switch ($varType) {
        case 'integer':
        case 'float':
        case 'double':
            return $var;

        case 'string':
            return strlen($var);

        case 'array':
            return count($var);

        case 'resource':
            $metadata = stream_get_meta_data($var);
            return $metadata['wrapper_type'] === 'plainfile' ? filesize($var) : strlen($var);

        default:
            throw new Exception("[Developer][Exception]: The given parameter has an invalid data type.");
            break;
    }
}

/**
 * Find out the number of digits in a number.
 *
 * @param int|float $var
 *
 * @return int
 */
function varDigits(int|float $var): int
{
    return $var !== 0 ? (int) (log($var, 10) + 1) : 1;
}

/**
 * Evaluate the given string variable to its corresponding data type value.
 *
 * @param string $var
 *
 * @return mixed
 */
function varEvaluateType(string $var)
{
    if (filter_var($var, FILTER_VALIDATE_BOOL)) {
        return (bool) $var;
    }

    if (filter_var($var, FILTER_VALIDATE_INT)) {
        return (int) $var;
    }

    if (filter_var($var, FILTER_VALIDATE_FLOAT)) {
        return (float) $var;
    }

    if (strtolower($var) === 'null') {
        return null;
    }

    return $var;
}
