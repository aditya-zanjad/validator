<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use DateTime;
use Throwable;
use DateMalformedStringException;

/**
 * Attempt to parse the given datetime into the \DateTime object
 *
 * @param   mixed       $value
 * @param   string      $format
 *
 * @return  bool|\DateTime
 */
function parseDateTime($value, string $format = '')
{
    if (!is_string($value)) {
        return false;
    }

    // *** NOTE => Commented temporarily. Might be needed in the future ***
    // $timezone = new DateTimeZone(ini_get('date.timezone') ?: date_default_timezone_get());

    try {
        if (empty($format)) {
            // Transform the given DateTime string to overcome PHP's DateTime parsing limitation(s).
            $value = str_replace('/', '-', trim($value));
            return new DateTime($value);
        }

        $dt = DateTime::createFromFormat($format, $value);

        if ($dt instanceof DateTime) {
            return $dt;
        }
    } catch (DateMalformedStringException $e) {
        // var_dump($e); exit;

        // If attempting to parse the date fails, attempt to parse it from the predefined formats.
        $formats = makeDateTimeFormats();

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $value);

            if ($dt instanceof DateTime) {
                return $dt;
            }
        }
    } catch (Throwable $e) {
        // var_dump($e); exit;
    }

    return false;
}

/**
 * Get a list of formats based on which we should parse the date if parsing fails.
 *
 * @param string $date
 *
 * @return array<int, string>
 */
function makeDateTimeFormats()
{
    return [
        'm-d-Y',
        'm/d/Y',
        'd/m/Y',
        'Y-d-m',
    ];
}
