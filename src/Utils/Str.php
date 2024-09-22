<?php

namespace AdityaZanjad\Validator\Utils;

/**
 * Get the part of the string before the certain given substring.
 *
 * @param   string  $str
 * @param   string  $subStr
 *
 * @return  string
 */
function str_before(string $str, string $subStr): string
{
    if (!str_contains($str, $subStr)) {
        return $str;
    }
    
    return substr($str, 0, strpos($str, $subStr));
}

/**
 * Get part of the substring after certain given substring.
 *
 * @param   string  $str
 * @param   string  $subStr
 *
 * @return  string
 */
function str_after(string $str, string $subStr): string
{
    if (!str_contains($str, $subStr)) {
        return $str;
    }

    return substr($str, strpos($str, $subStr) + strlen($subStr), strlen($str));
}
