<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Traits;

use DateTime;
use DateMalformedStringException;

trait DateTimeHelpers
{
    protected array $formats = [
        // --- I. ISO 8601 & Technical Standards (Best for APIs/Logging) ---

        // ISO 8601 (The global standard)
        'c',                 // e.g., 2025-11-14T16:44:20+05:30 (Short-hand)
        'Y-m-d\TH:i:sP',     // Full ISO 8601
        'Y-m-d\TH:i:s.uP',   // ISO 8601 with Microseconds
        'Y-m-d\TH:i:s',      // ISO 8601 without required timezone suffix
        'Ymd\THis',          // Basic ISO 8601 (no separators)

        // RFC 2822 (Used in HTTP headers, cookies, email)
        'r',                 // e.g., Fri, 14 Nov 2025 16:44:20 +0530 (Short-hand)
        'D, d M Y H:i:s O',  // Full RFC 2822

        // Unix Epoch Timestamp (Integer)
        'U',                 // e.g., 1731610994

        // --- II. Database & Standard Formats (SQL) ---

        // Standard Timestamp/Datetime
        'Y-m-d H:i:s',       // e.g., 2025-11-14 16:44:20
        'Y-m-d H:i:s.u',     // Standard with microseconds
        'Y-m-d H:i',         // Without seconds
        'Y-m-d',             // Date only

        // --- III. Regional & Separator Variations ---

        // US Format Variants (Month/Day/Year)
        'm/d/Y H:i:s',
        'm/d/Y g:i:s a',
        'm-d-Y',
        'm/d/y',

        // European/International Format Variants (Day/Month/Year)
        'd/m/Y H:i:s',
        'd/m/Y H:i',
        'd-m-Y',
        'd.m.Y',

        // Year-First Formats
        'Y/m/d',
        'Y.m.d',

        // --- IV. Time and Textual Formats ---

        // 24-Hour Time
        'H:i:s',
        'H:i',

        // 12-Hour Time
        'h:i:s A',           // e.g., 04:44:20 PM
        'g:i a',             // e.g., 4:44 pm
        'h:i A',             // e.g., 04:44 PM

        // Full Textual
        'l, F j, Y',         // e.g., Friday, November 14, 2025
        'F j, Y',            // e.g., November 14, 2025
        'F jS, Y',           // e.g., November 14th, 2025
    ];

    protected function tryParseDateTime(mixed $givenDateTime): ?DateTime
    {
        try {
            return new DateTime($givenDateTime);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
        }

        foreach ($this->formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $givenDateTime);

            if ($dateTime !== false) {
                return $dateTime;
            }
        }

        return null;
    }

    protected function IsValidDateTime(mixed $givenDateTime): bool
    {
        if (!\is_string($givenDateTime)) {
            return false;
        }

        try {
            new DateTime($givenDateTime);
            return true;
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
        }

        return false;
    }

    protected function isDateTimeParsable(mixed $givenDateTime): bool
    {
        if (!\is_string($givenDateTime)) {
            return false;
        }

        foreach ($this->formats as $format) {
            if (DateTime::createFromFormat($format, $givenDateTime) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function isDateTimeWithFormatParsable(string $givenDateTime, string $format): bool
    {
        if (!\is_string($givenDateTime)) {
            return false;
        }

        return (bool) DateTime::createFromFormat($format, $givenDateTime);
    }
}
