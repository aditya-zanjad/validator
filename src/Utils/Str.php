<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

/**
 * Get the part of the string before the certain given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  string
 */
function str_before(string $str, string $sub): string
{
    if (!\str_contains($str, $sub)) {
        return $str;
    }

    return \substr($str, 0, \strpos($str, $sub));
}

/**
 * Get part of the substring after certain given substring.
 *
 * @param   string  $str
 * @param   string  $sub
 *
 * @return  string
 */
function str_after(string $str, string $sub): string
{
    if (!\str_contains($str, $sub)) {
        return $str;
    }

    return \substr($str, \strpos($str, $sub) + \strlen($sub), \strlen($str));
}

/**
 * Check if a string contains a given substring or not.
 *
 * @param   string  $str
 * @param   string  $sub
 * 
 * @return  bool
 */
function str_contains_v2(string $str, string $sub): bool
{
    if (\function_exists('str_contains')) {
        return \str_contains($str, $sub);
    }

    return \strpos($str, $sub) !== false;
}
