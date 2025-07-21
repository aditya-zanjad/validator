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
    if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
        $value = "@{$value}";
    }

    if (!is_string($value)) {
        return false;
    }

    $value      =   trim((string) $value);
    $dateTime   =   false;

    try {
        $dateTime = empty($format)
            ? new DateTime($value)
            : DateTime::createFromFormat($format, $value);

        if ($dateTime === false) {
            return false;
        }
    } catch (DateMalformedStringException $e) {
        // var_dump($e); exit;
        $dateTime = strtotime($value);

        if ($dateTime === false) {
            return false;
        }

        return $dateTime;
    } catch (Throwable $e) {
        // var_dump($e); exit;
    }

    if ($dateTime === false) {
        try {
            $value      =   str_replace('/', '-', $value);
            $dateTime   =   new DateTime($value);
        } catch (Throwable $e) {
            // var_dump($e); exit;
            $dateTime = false;
        }
    }

    return $dateTime;
}

/**
 * Get a list of the DateTime formats to try when parsing the DateTime string.
 *
 * @return array
 */
function getTriableDateTimeFormats(): array
{
    return [
        // ISO 8601 (most preferred)
        'Y-m-d\TH:i:sP',    // 2025-07-21T16:34:19+05:30 (with timezone)
        'Y-m-d\TH:i:s.uP',  // 2025-07-21T16:34:19.123456+05:30 (with microseconds)
        'Y-m-d\TH:i:s',     // 2025-07-21T16:34:19
        'Y-m-d H:i:s',      // 2025-07-21 16:34:19
        'Y-m-d',            // 2025-07-21
        'Y/m/d H:i:s',      // 2025/07/21 16:34:19
        'Y/m/d',            // 2025/07/21

        // US common formats (MM/DD/YYYY) - Ambiguous if D is <= 12
        'm/d/Y H:i:s',      // 07/21/2025 16:34:19
        'm/d/Y',            // 07/21/2025
        'm-d-Y H:i:s',      // 07-21-2025 16:34:19
        'm-d-Y',            // 07-21-2025

        // European/Indian   common formats (DD/MM/YYYY) - Ambiguous if M is <= 12
        'd/m/Y H:i:s',      // 21/07/2025 16:34:19
        'd/m/Y',            // 21/07/2025
        'd-m-Y H:i:s',      // 21-07-2025 16:34:19
        'd-m-Y',            // 21-07-2025
        'd.m.Y H:i:s',      // 21.07.2025 16:34:19
        'd.m.Y',            // 21.07.2025

        // Other common textual formats
        'F j, Y H:i:s',     // July 21, 2025 16:34:19
        'F j, Y',           // July 21, 2025
        'j F Y H:i:s',      // 21 July 2025 16:34:19
        'j F Y',            // 21 July 2025
        'M d, Y',           // Jul 21, 2025
        'd M Y',            // 21 Jul 2025

        // Formats with tw  o-digit years (less preferred due to year ambiguity)
        'm/d/y',            // 07/21/25
        'd/m/y',            // 21/07/25
        'Y-m-d H:i',        // 2025-07-21 16:34 (no seconds)
        'H:i:s Y-m-d',      // 16:34:19 2025-07-21
    ];
}
