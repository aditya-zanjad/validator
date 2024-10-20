<?php

namespace AdityaZanjad\Validator\Utils;

use Exception;

/**
 * Get size of the given variable data depending on its type.
 *
 * @param   mixed       $value      =>  The value whose size we want to calculate.
 * @param   null|string $exception  =>  If not NULL, an exception message will be thrown when the size of the given value could not calculated.
 *
 * @throws  \Exception
 *
 * @return  null|int
 */
function size_of(mixed $value, ?string $exception): ?int
{
    $size = match (gettype($value)) {
        'integer', 'float'  =>  $value,
        'string'            =>  strlen($value),
        'array'             =>  count($value),
        'resource'          =>  filesize($value),
        default             =>  null
    };
    
    if (is_null($size) && !is_null($exception)) {
        throw new Exception($exception);
    }
    
    return $size;
}

/**
 * Filter the given string value to its corresponding data type.
 *
 * @param string $value
 *
 * @return mixed
 */
function filter_value(string $value): mixed
{
    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN | FILTER_VALIDATE_INT | FILTER_VALIDATE_FLOAT);
    return $value !== 'null' ? $value : null;
}

/**
 * Filter the given array of values to their respective data types.
 *
 * @param array<int, string> $values
 *
 * @return array<int, string>
 */
function filter_values(array $values): mixed
{
    return array_map(fn ($value) => filter_value($value), $values);
}
