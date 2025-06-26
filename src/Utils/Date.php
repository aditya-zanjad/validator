<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use DateTime;
use Throwable;

/**
 * Attempt to parse the given datetime into the \DateTime object
 *
 * @param   int|string  $value
 * @param   string      $format
 * 
 * @return  bool|\DateTime
 */
function parseDateTime($value, string $format = '')
{
    if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
        $value = "@{$value}";
    }

    try {
        $dateTime = !empty($format) 
            ? DateTime::createFromFormat($format, $value) 
            : new DateTime($value);

        if ($dateTime === false) {
            return false;
        }
    } catch (Throwable $e) {
        // var_dump($e); exit;
        return false;
    }

    return $dateTime;
}
