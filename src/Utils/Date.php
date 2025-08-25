<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

use DateTime;
use Throwable;

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

    try {
        if (!empty($format)) {
            return DateTime::createFromFormat($format, $value);
        }

        return new DateTime($value);
    } catch (Throwable $e) {
        // var_dump($e); exit;

        // If parsing the date fails, attempt to parse it with some pre-defined date formats.
        $formats = makeDateTimeFormats();

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $value);

            if ($dt instanceof DateTime) {
                return $dt;
            }
        }
    }

    return false;
}

/**
 * Get a list of formats based on which we should parse the date if parsing fails.
 *
 * @return array<int, string>
 */
function makeDateTimeFormats(): array
{
    return [
        // --- ISO 8601 & Database Formats (Most Recommended & Unambiguous) ---
        'Y-m-d H:i:s.v',    // ISO 8601 with milliseconds (e.g., 2025-08-04 16:12:44.123)
        'Y-m-d H:i:s',      // Standard DATETIME format (e.g., 2025-08-04 16:12:44)
        'Y-m-d\TH:i:s',     // ISO 8601 with 'T' separator (e.g., 2025-08-04T16:12:44)
        'Y-m-d\TH:i:sP',    // ISO with timezone (e.g., 2025-08-04T16:12:44+05:30)
        'Y-m-d',            // Standard DATE format (e.g., 2025-08-04)

        // --- Programming & API Formats ---
        'U',                // Unix Timestamp (e.g., 1754388600)
        'D, d M Y H:i:s O', // RFC 2822 (e.g., Mon, 04 Aug 2025 16:12:44 +0530)
        'c',                // ISO 8601 full format (e.g., 2025-08-04T16:12:44+05:30)

        // --- Common User Input with Separators ---
        'd-m-Y H:i:s',      // European with seconds
        'm-d-Y H:i:s',      // American with seconds
        'd/m/Y H:i:s',      // European with seconds
        'm/d/Y H:i:s',      // American with seconds

        'd-m-Y',            // European (e.g., 04-08-2025)
        'd/m/Y',            // European (e.g., 04/08/2025)
        'd.m.Y',            // European (e.g., 04.08.2025)
        'm-d-Y',            // American (e.g., 08-04-2025)
        'm/d/Y',            // American (e.g., 08/04/2025)
        'm.d.Y',            // American (e.g., 08.04.2025)
        'y-m-d',            // 2-digit year (e.g., 25-08-04)
        'd-M-y',            // Abbreviated month with 2-digit year (e.g., 04-Aug-25)

        // --- Textual & Flexible Formats ---
        'l, F j, Y',        // Long format (e.g., Monday, August 4, 2025)
        'F j, Y',           // e.g., 'August 4, 2025'
        'M j, Y',           // e.g., 'Aug 4, 2025'
        'j M Y',            // e.g., '4 Aug 2025'

        // --- Time-Only Formats ---
        'H:i:s',            // 24h with seconds (e.g., 16:12:44)
        'H:i',              // 24h without seconds (e.g., 16:12)
        'h:i:s A',          // 12h with seconds (e.g., 04:12:44 PM)
        'h:i A',            // 12h without seconds (e.g., 04:12 PM)
        'Hi',               // No separator (e.g., 1612)
    ];
}
