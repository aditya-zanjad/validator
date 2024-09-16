<?php

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Filled implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (empty($value)) {
            return 'The field {:attribute} is required & must be a non-empty value.';
        }

        return true;
    }
}
