<?php

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeBool implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_BOOL)) {
            return 'The field {:attribute} must be a boolean value.';
        }

        return true;
    }
}
