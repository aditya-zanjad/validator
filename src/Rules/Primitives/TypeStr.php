<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeStr implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field {$attribute} must be a string.";
        }

        return true;
    }
}
