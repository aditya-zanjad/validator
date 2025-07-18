<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use DateTime;
use Throwable;
use DateMalformedStringException;

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
    } catch (DateMalformedStringException $e) {
        // var_dump($e); exit;
        $dateTime = strtotime($value);

        if ($dateTime === false) {
            return false;
        }

        return true;
    } catch (Throwable $e) {
        // var_dump($e); exit;
        return false;
    }

    return $dateTime;
}
