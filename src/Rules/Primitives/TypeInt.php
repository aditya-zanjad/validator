<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeInt implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            return "The field {$attribute} must be a string.";
        }

        return true;
    }
}
