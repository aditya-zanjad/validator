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

        case 'boolean':
            return ((bool) $var) ? 1 : 0;

        case 'string':
            return \strlen($var);

        case 'array':
            return \count($var);

        case 'resource':
            $metadata = \stream_get_meta_data($var);

            switch ($metadata['wrapper_type']) {
                case 'plainfile':
                    return \filesize($var);

                default:
                    return \strlen($var);
            }

            // no break
        default:
            throw new Exception("[Developer][Exception]: The given parameter has an invalid data type.");
            break;
    }
}

/**
 * Find out the number of digits in a number.
 *
 * @param   int|float|string $var
 *
 * @throws  \Exception
 *
 * @return  int
 */
function varDigits(int|float|string $var): int
{
    if (filter_var($var, FILTER_VALIDATE_INT) === false || filter_var($var, FILTER_VALIDATE_FLOAT) === false) {
        throw new Exception("[Developer][Exception]: The parameter must be either an Integer OR a Float value.");
    }

    if ($var === 0) {
        return 1;
    }

    return (int) (\log($var, 10) + 1);
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
