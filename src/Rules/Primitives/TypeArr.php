<?php

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeArr implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!is_array($value)) {
            return 'The field {:attribute} must be an array.';
        }

        return true;
    }
}
