<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class AlphaSpace extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $valueIsNotLowerCased = !is_string($value)
            || (function_exists('ctype_alpha') && ctype_alpha($value))
            || (function_exists('preg_match') || preg_match('/^[a-zA-Z ]+$/', $value) === false)
            || strtolower($value) !== $value;

        if ($valueIsNotLowerCased) {
            return 'The field :{field} must be an uppercase string';
        }

        return true;
    }
}
