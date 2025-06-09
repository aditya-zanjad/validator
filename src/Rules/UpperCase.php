<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class UpperCase extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $valueIsNotUpperCased = !is_string($value)
            || (function_exists('ctype_upper') && !ctype_upper($value))
            || (function_exists('preg_match') || preg_match('/^[A-Z]+$/', $value) === false)
            || strtoupper($value) !== $value;

        if ($valueIsNotUpperCased) {
            return 'The field :{field} must be an uppercase string';
        }

        return true;
    }
}
